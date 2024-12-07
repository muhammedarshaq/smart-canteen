<?php
require_once '../database.php';
class Menu
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function addFood($title, $price, $image, $category)
    {
        $conn = $this->db->getConnection();

        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO menu (`Food_Name`, `Price`, `Image`, `Category_ID`) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $title, $price, $image, $category);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
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
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $menu = new Menu();

    $title = $_POST['title'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $category = $_POST['category'];

    if ($menu->addFood($title, $price, $image, $category)) {
        echo "Food added succefully!";
    } else {
        echo "Food adding failed.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order - Smart Canteen Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            background-color: transparent;
            position: relative;
            z-index: 10;
        }

        .wrapper {
            background-color: rgba(255, 255, 255, 0.8);
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .wrapper:hover {
            transform: translateY(-5px);
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 28px;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            text-align: center;
        }

        .form-table {
            width: 100%;
            margin-top: 20px;
        }

        .form-table tr td {
            padding: 15px;
            vertical-align: top;
        }

        .form-table tr td:first-child {
            width: 150px;
            font-weight: bold;
            color: #2c3e50;
        }

        .form-table input[type="text"],
        .form-table input[type="number"],
        .form-table textarea,
        .form-table select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-table input[type="text"]:focus,
        .form-table input[type="number"]:focus,
        .form-table textarea:focus,
        .form-table select:focus {
            outline: none;
            border-color: #3498db;
        }

        .btn-primary {
            width: 100%;
            background-color: #3498db;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: scale(1.02);
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 1em;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <div class="background">
            <div class="circle-pink"></div>
            <div class="circle-blue"></div>
            <div class="circle-pink2"></div>
            <div class="circle-blue2"></div>
        </div>
        <div class="main-content">
            <div class="wrapper">
                <h1><i class="fas fa-plus-circle"></i> Create Order</h1>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="add-food-form">
                    <table class="form-table">
                        <tr>
                            <td>Title:</td>
                            <td>
                                <input type="text" name="title" placeholder="Enter food title" required>
                            </td>
                        </tr>
                        <tr>
                            <td>Price:</td>
                            <td>
                                <input type="number" name="price" placeholder="Enter price" required>
                            </td>
                        </tr>
                        <tr>
                            <td>Select Image:</td>
                            <td>
                                <input type="text" name="image" placeholder="Enter image URL" required>
                            </td>
                        </tr>
                        <tr>
                            <td>Category:</td>
                            <td>
                                <select name="category" required>
                                    <option value="">Select Category</option>
                                    <?php
                                    $menu = new Menu();
                                    $categories = $menu->getCategories();
                                    if ($categories) {
                                        while ($row = $categories->fetch_assoc()) {
                                            echo "<option value='" . $row['Category_ID'] . "'>" . $row['Category_Name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: right;">
                                <input type="submit" name="submit" value="Add Food" class="btn-primary">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</body>
<style>
    /* Background Animation */
    .background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        overflow: hidden;
        z-index: -1;
        pointer-events: none;
    }

    .circle-pink,
    .circle-blue,
    .circle-pink2,
    .circle-blue2 {
        position: absolute;
        width: 600px;
        height: 600px;
        border-radius: 50%;
        filter: blur(70px);
        animation: float 10s infinite ease-in-out;
    }

    .circle-pink {
        top: -100px;
        left: -150px;
        background-color: #8974ff;
    }

    .circle-blue {
        bottom: -200px;
        right: -200px;
        background-color: #8974ff;
    }

    .circle-pink2 {
        top: -200px;
        right: 300px;
        background-color: #ff7bfb;
    }

    .circle-blue2 {
        bottom: -400px;
        left: 60px;
        background-color: #ff7bfb;
    }
</style>

</html>