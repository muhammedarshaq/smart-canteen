<?php
session_start();
require_once '../database.php';


if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}


class OrderStatus
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getOrderStatusByEmail($email)
    {
        $conn = $this->db->getConnection();

        $email = $_SESSION['email'];
        $user_query = "SELECT ID FROM customer WHERE Email = ?";
        $stmt = $conn->prepare($user_query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user_result = $stmt->get_result();
        $user = $user_result->fetch_assoc();
        $customer_id = $user['ID'];

        $sql = "SELECT * FROM order_status WHERE Customer_ID = ?";
        $sql .= " ORDER BY Customer_ID DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();


        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }

    public function getOrderByEmail($email)
    {
        $conn = $this->db->getConnection();

        $email = $_SESSION['email'];
        $user_query = "SELECT ID FROM customer WHERE Email = ?";
        $stmt = $conn->prepare($user_query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user_result = $stmt->get_result();
        $user = $user_result->fetch_assoc();
        $customer_id = $user['ID'];

        $sql = "SELECT * FROM `order` WHERE Customer_ID = ?";
        $sql .= " ORDER BY Order_ID DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();


        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }
}

$orderStatus = new OrderStatus();
$result = $orderStatus->getOrderStatusByEmail($_SESSION['email']);
if (!$result) {
    header("Location: home.php");
    exit();
}
$order_result = $orderStatus->getOrderByEmail($_SESSION['email']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Reset and base styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px 20px 70px;
            background: linear-gradient(to right, #8974FF, #FF7BFB);
            min-height: 100vh;
        }

        /* Container styles */
        .status-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Status elements */
        .status-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eeeeee;
        }

        .status-label {
            font-weight: 600;
            color: #333333;
        }

        .status-value {
            color: #008000;
        }

        .status-pending {
            color: #ffa500;
            font-weight: 500;
        }

        .status-completed {
            color: #008000;
            font-weight: 500;
        }

        /* Bottom navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #ffffff;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            padding: 12px 0;
            z-index: 1000;
        }

        .nav-items {
            display: flex;
            justify-content: space-around;
            align-items: center;
            max-width: 600px;
            margin: 0 auto;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #666666;
            font-size: 12px;
            transition: color 0.3s ease;
        }

        .nav-item i {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .nav-item.active {
            color: #007bff;
        }

        /* Tracking steps */
        .tracking-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 50px;
            position: relative;
            padding: 0 20px;
        }

        .tracking-steps::before {
            content: "";
            position: absolute;
            top: 25px;
            left: 50px;
            right: 50px;
            height: 4px;
            background: #e0e0e0;
            z-index: 1;
        }

        .step {
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .step-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #ffffff;
            border: 4px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            transition: all 0.3s ease;
        }

        .step.active .step-icon {
            border-color: #4caf50;
            background: #4caf50;
            color: #ffffff;
        }

        .step-label {
            font-weight: 600;
            color: #ffffff;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="status-container">
        <div class="status-header">
            <h2>Order Status</h2>
        </div>

        <div class="status-item">
            <span class="status-label">Order ID:</span>
            <span class="status-value">#<?php echo $order_result["Order_ID"] ?></span>
        </div>

        <div class="status-item">
            <span class="status-label">Status:</span>
            <span class="status-value status-pending"><?php echo $result["Order_Status"] ?></span>
        </div>
    </div>
    <br>
    <br>
    <br>
    <div class="tracking-steps">
        <div class="step <?php echo $result['Order_Status'] == "Pending" ? "active" : "" ?>">
            <div class="step-icon">1</div>
            <div class="step-label">Order Accepted</div>
        </div>
        <div class="step <?php echo $result['Order_Status'] == "Preparing" ? "active" : "" ?>">
            <div class="step-icon">2</div>
            <div class="step-label">Preparing</div>
        </div>
        <div class="step <?php echo $result['Order_Status'] == "Ready" ? "active" : "" ?>">
            <div class="step-icon">3</div>
            <div class="step-label">Ready</div>
        </div>
        <div class="step <?php echo $result['Order_Status'] == "Completed" ? "active" : "" ?>">
            <div class="step-icon">4</div>
            <div class="step-label">Completed</div>
        </div>
    </div>
    <nav class="bottom-nav">
        <div class="nav-items">
            <a href="home.php" class="nav-item">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="status.php" class="nav-item active" style="color: #d63384;">
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