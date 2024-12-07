<?php
require_once '../database.php';

session_start();

class Foods
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getFoodsByCategoryName($categoryName)
    {
        $categoryId = $this->getCategoryIdByName($categoryName);
        return $this->getFoods($categoryId);
    }

    private function getCategoryIdByName($name)
    {
        $conn = $this->db->getConnection();
        $sql = "SELECT Category_ID FROM category WHERE Category_Name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['Category_ID'];
        }
        return false;
    }

    public function getFoods($categoryId)
    {
        $conn = $this->db->getConnection();
        $sql = "SELECT * FROM menu WHERE Category_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result;
        }
        return false;
    }
}

// Get category from URL parameters
if (isset($_GET['category'])) {
    $categoryName = $_GET['category'];

    // Get foods by category
    $foods = new Foods();
    $result = $foods->getFoodsByCategoryName($categoryName);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Canteen - Foods</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #8974FF, #FF7BFB);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .top-bar {
            background-color: #ffffff;
            padding: 10px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .top-bar h1 {
            margin: 0;
            padding-left: 10px;
            color: #333;
        }

        .food-list {
            margin: 20px 0;
        }

        .food-grid {
            display: flex;
            flex-direction: column;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 20px;
        }

        .food-card {
            background: #c7bbfc;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
            display: flex;
            gap: 20px;
        }

        .food-card:hover {
            transform: translateY(-5px);
        }

        .food-card img {
            width: 50%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }

        .food-card h3 {
            margin: 10px 0;
            font-size: 16px;
            color: #333;
        }

        .food-card p {
            color: #000000;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .food-card button {
            background-color: #9fa0f4;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }

        .food-card button:hover {
            background-color: #0056b3;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: white;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
        }

        .nav-items {
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
        }

        .nav-item {
            text-decoration: none;
            color: #666;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 12px;
        }

        .nav-item i {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .nav-item.active {
            color: #007bff;
        }
    </style>
</head>

<body>
    <div class="top-bar">
        <h1 style="color: black; text-transform: capitalize;"><?php echo htmlspecialchars($categoryName); ?></h1>
    </div>
    <div class="container">
        <div class="food-list">
            <!-- <h2 style="color: white;">Available Foods</h2> -->
            <h3 style="color: white; text-transform: capitalize;"></h3>
            <div class="food-grid">
                <?php
                if (isset($result) && $result) {
                    while ($food = $result->fetch_assoc()) {
                        echo '<div class="food-card">
                            <img src="' . $food['Image'] . '" alt="' . $food['Food_Name'] . '">
                            <div class="det"><h3>' . $food['Food_Name'] . '</h3>
                            <p>Rs ' . number_format($food['Price'], 2) . '</p>
                            <button onclick="addToCart(' . $food['Menu_ID'] . ', \'' . $food['Food_Name'] . '\', ' . $food['Price'] . ')">Add to Cart</button></div>
                        </div>';
                    }
                } else {
                    echo '<p>No foods available for this category.</p>';
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        function addToCart(menuId, foodName, price) {
            fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `menu_id=${menuId}&food_name=${foodName}&price=${price}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'cart.php';
                    } else {
                        alert('Failed to add item to cart');
                    }
                });
        }
    </script>

    <nav class="bottom-nav">
        <div class="nav-items">
            <a href="home.php" class="nav-item">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="status.php" class="nav-item">
                <i class="fas fa-utensils"></i>
                <span>Status</span>
            </a>
            <a href="cart.php" class="nav-item">
                <i class="fas fa-shopping-cart"></i>
                <span>Cart</span>
            </a>
            <a href="profile.php" class="nav-item">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
        </div>
    </nav>
</body>

</html>