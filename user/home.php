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
$t = new Categories();
$name = $t->getUsername($_SESSION['email']);
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
            background-color: #f5f5f5;
        }

        .container {
            padding: 0 20px;
            max-width: 1200px;
            margin: 0 auto;
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

        .search-and-greeting {
            background-color: #ffffff;
            padding: 20px 0;
        }

        .search-bar {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .search-bar input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        .search-bar button {
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .food-categories {
            padding: 20px 0;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 20px;
        }

        .category-card {
            background: white;
            border-radius: 8px;
            padding: 10px;
            text-decoration: none;
            color: #333;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .category-card:hover {
            transform: translateY(-5px);
        }

        .category-title {
            margin: 10px 0;
            text-align: center;
            font-size: 16px;
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

        .greeting-container {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 12px;
            background: linear-gradient(145deg, #e0d6ff, #f0f4ff);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        h2 {
            margin: 0 0 10px;
            color: #d63384;
            /* Pink color for the text */
            font-size: 1.8rem;
            font-weight: 600;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            padding: 8px 12px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-bar input {
            flex-grow: 1;
            border: none;
            outline: none;
            font-size: 16px;
            padding: 6px;
            background: transparent;
            margin: 0 8px;
            color: #333;
        }

        .search-bar .search-icon,
        .search-bar .mic-icon {
            font-size: 18px;
            color: #666;
        }

        .search-bar .mic-icon {
            cursor: pointer;
        }

        /* Adjust font size and layout for smaller devices */
        @media screen and (max-width: 480px) {
            .greeting-container {
                padding: 15px;
            }

            h2 {
                font-size: 1.5rem;
            }

            .search-bar {
                display: flex;
                padding: 6px 10px;
            }

            .search-bar input {
                font-size: 14px;
            }

            .search-bar .search-icon,
            .search-bar .mic-icon {
                font-size: 16px;
            }
        }

        @media screen and (max-width: 768px) {
            .container {
                padding: 0 10px;
            }

            .search-bar {
                flex-direction: column;
            }

            .search-bar input,
            .search-bar button {
                width: 100%;
            }

            .category-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }

            .top-bar h1 {
                font-size: 1.5em;
            }

            .nav-items {
                padding: 5px 0;
            }

            .nav-item {
                font-size: 10px;
            }

            .nav-item i {
                font-size: 16px;
            }
        }

        @media screen and (max-width: 480px) {
            .category-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="top-bar">
        <h1>Home</h1>
    </div>
    <div class="greeting-container">
        <h2>Hi <?php echo $name ?>,</h2>
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search" />
        </div>
    </div>
    <div class="food-categories">
        <div class="container">
            <h2 style="margin: 20px 0;">Food Categories</h2>
            <div class="category-grid">
                <?php
                $categories = new Categories();
                $result = $categories->getCategories();

                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<a href="food.php?category=' . strtolower($row['Category_Name']) . '" class="category-card">
                                <img src="' . strtolower($row['Image']) . '" alt="' . $row['Category_Name'] . '" style="width: 100%; height: 120px; object-fit: cover; border-radius: 8px;">
                                <h3 class="category-title">' . $row['Category_Name'] . '</h3>
                            </a>';
                    }
                }
                ?>

            </div>
        </div>
    </div>

    <nav class="bottom-nav">
        <div class="nav-items">
            <a href="home.php" class="nav-item active" style="color: #d63384;">
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
</div>