<?php
require_once '../database.php';

session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

class Categories
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getCategories()
    {
        $conn = $this->db->getConnection();
        $sql = "SELECT * FROM category";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function getUsername($email)
    {
        $conn = $this->db->getConnection();
        $sql = "SELECT Name FROM customer WHERE Email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($name);
        $stmt->fetch();
        return $name;
    }
}

$categories = new Categories();
$name = $categories->getUsername($_SESSION['email']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Canteen - Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #8974FF, #FF7BFB);
        }

        .top-bar {
            background-color: #ffffff;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar h1 {
            margin: 0;
            color: #333;
            font-size: 1.5em;
        }

        .greeting-container {
            background: linear-gradient(145deg, #e0d6ff, #f0f4ff);
            padding: 20px;
            border-radius: 12px;
            margin: 20px auto;
            max-width: 90%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .greeting-container h2 {
            margin: 0 0 10px;
            color: #d63384;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.9);
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 8px 12px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-bar input {
            flex-grow: 1;
            border: none;
            outline: none;
            padding: 6px 10px;
            font-size: 16px;
            background: transparent;
        }

        .search-bar i {
            color: #999;
            font-size: 1.2em;
            margin-right: 10px;
        }

        .food-categories {
            margin: 20px 0;
            padding: 0 20px;
        }

        .food-categories h2 {
            margin-bottom: 15px;
            color: #333;
            font-size: 1.6rem;
            font-weight: bold;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
        }

        .category-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            color: #333;
            padding: 10px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
        }

        .category-card img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }

        .category-card .category-title {
            text-align: center;
            margin: 10px 0 0;
            font-size: 1rem;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: #fff;
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
            color: #d63384;
        }
    </style>
</head>

<body>
    <div class="top-bar">
        <h1>Smart Canteen</h1>
    </div>

    <div class="greeting-container">
        <h2>Hello, <?php echo $name; ?>!</h2>
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search for food or categories" />
        </div>
    </div>

    <div class="food-categories">
        <div class="container">
            <h2 style="color: #ddd;">Food Categories</h2>
            <div class="category-grid">
                <?php
                $result = $categories->getCategories();
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<a href="food.php?category=' . strtolower($row['Category_Name']) . '" class="category-card">
                                <img src="' . strtolower($row['Image']) . '" alt="' . $row['Category_Name'] . '">
                                <h3 class="category-title">' . $row['Category_Name'] . '</h3>
                              </a>';
                    }
                } else {
                    echo '<p>No categories available at the moment.</p>';
                }
                ?>
            </div>
        </div>
    </div>

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