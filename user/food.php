<?php
require_once '../database.php';

session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

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


    // Get category ID from category name
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

    // Get category ID
    $foods = new Foods();
    $result = $foods->getFoodsByCategoryName($categoryName);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Canteen - Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            padding-bottom: 70px;
            /* Space for bottom nav */
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 15px;
        }

        .header {
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .menu-items {
            display: flex;
            gap: 30px;
        }

        .menu-items a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .menu-items a:hover {
            color: #007bff;
        }

        .main-content {
            padding: 40px 0;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #fff;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            padding: 10px 0;
            z-index: 1000;
        }

        .nav-items {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #666;
            font-size: 12px;
        }

        .nav-item i {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .nav-item.active {
            color: #007bff;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .category-card {
            background: #fff;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .category-icon {
            font-size: 30px;
            color: #007bff;
            margin-bottom: 10px;
        }

        .category-title {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .category-description {
            font-size: 12px;
            color: #666;
        }

        @media (max-width: 480px) {
            .category-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="food-categories">
        <div class="container">
            <h2 style="margin: 20px 0;">Foods</h2>
            <div class="category-grid">
                <?php
                if (isset($result) && $result) {
                    echo '<div class="food-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-top: 20px;">';
                    while ($food = $result->fetch_assoc()) {
                        echo '<div class="food-card" style="background: #fff; border-radius: 10px; padding: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            <img src="' . $food['Image'] . '" alt="' . $food['Food_Name'] . '" style="width: 100%; height: 120px; object-fit: cover; border-radius: 8px;">
                            <h3 style="margin: 10px 0;">' . $food['Food_Name'] . '</h3>
                            <p style="color: #007bff; font-weight: bold;">Rs ' . number_format($food['Price'], 2) . '</p>
                            <button onclick="addToCart(' . $food['Menu_ID'] . ', \'' . $food['Food_Name'] . '\', ' . $food['Price'] . ')" style="background: #007bff; color: white; border: none; padding: 8px 16px; border-radius: 5px; margin-top: 10px; cursor: pointer;">
                                Add to Cart
                            </button>
                        </div>';
                    }
                    echo '</div>';
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
            <a href="home.php" class="nav-item active">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="status.php" class="nav-item">
                <i class="fas fa-utensils"></i>
                <span>Status</span>
            </a>
            <a href="cart.php" class="nav-item">
                <i class="fas fa-shopping-bag"></i>
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
</div>