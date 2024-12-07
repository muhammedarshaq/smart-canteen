<?php
require_once '../database.php';

session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

class Customer
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getCustomerByEmail($email)
    {
        $conn = $this->db->getConnection();
        $sql = "SELECT * FROM customer WHERE Email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }
}

$customer = new Customer();
$user = $customer->getCustomerByEmail($_SESSION['email']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            padding-bottom: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        .profile-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto;
            display: block;
            object-fit: cover;
            border: 3px solid #3498db;
        }

        .profile-form {
            display: grid;
            gap: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .update-btn {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .update-btn:hover {
            background-color: #2980b9;
        }

        /* Add bottom nav styles */
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
    </style>
</head>

<body>
    <div class="profile-container">
        <div class="profile-header">
            <img src="../images/user.png" alt="Profile Picture" class="profile-picture">
            <h2>Profile Management</h2>
        </div>

        <form class="profile-form" method="POST" action="update-profile.php">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" value="<?php echo $user["Name"] ?>">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $user["Email"] ?>">
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="<?php echo $user["Phone_Number"] ?>">
            </div>

            <button type="submit" class="update-btn">Update Profile</button>
        </form>
        <form action="logout.php" method="POST" style="margin-top: 20px;">
            <button type="submit" class="update-btn" style="background-color: #dc3545;">Logout</button>
        </form>
    </div>
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
                <i class="fas fa-shopping-bag"></i>
                <span>Cart</span>
            </a>
            <a href="profile.php" class="nav-item active" style="color: #d63384;">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
        </div>
    </nav>
</body>

</html>