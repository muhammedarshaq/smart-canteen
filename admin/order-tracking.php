<?php
require_once '../database.php';
class Order
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getLastOrder()
    {
        $conn = $this->db->getConnection();
        $sql = "SELECT * FROM `order` ORDER BY Order_ID DESC LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function getLastOrderStatus()
    {
        $conn = $this->db->getConnection();
        $sql = "SELECT * FROM `order_status` ORDER BY Customer_ID DESC LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }
}
$order = new Order();
$orderStatus = $order->getLastOrderStatus();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];

    $db = new Database();
    $conn = $db->getConnection();



    $sql = "UPDATE `order_status` SET `Order_Status` = ? WHERE Customer_ID = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $order_status, $orderStatus['Customer_ID']);

    if ($stmt->execute()) {
        echo "Status updated successfully!";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$lastOrder = $order->getLastOrder();
$orderStatus = $order->getLastOrderStatus();

if (!$lastOrder || !$orderStatus) {
    echo "No orders found!";
    header("Location: profile.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f5f5f5;
        }

        .container {
            display: flex;
            flex-direction: row;
            width: 100%;
        }

        .sidebar {
            width: 250px;
            background-color: #333;
            min-height: 100vh;
            padding: 20px;
            color: white;
        }

        .main-content {
            flex: 1;
            padding: 40px;
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        h1 i {
            margin-right: 10px;
        }

        .wrapper {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
            width: 100%;
            max-width: 900px;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .details-container {
            flex: 1;
        }

        .details-container .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 16px;
            color: #555;
        }

        .details-container .detail-row span:first-child {
            font-weight: bold;
            color: #333;
        }

        .image-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .image-container img {
            width: 300px;
            height: 300px;
            border-radius: 50px;
            object-fit: cover;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dropdown-container {
            margin-top: 20px;
            text-align: center;
        }

        .form-control {
            padding: 10px 15px;
            border: 2px solid #4caf50;
            border-radius: 8px;
            background-color: #fff;
            font-size: 14px;
            color: #333;
            width: 220px;
            cursor: pointer;
            outline: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-control:hover {
            border-color: #333;
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
    <div class="background">
        <div class="circle-pink"></div>
        <div class="circle-blue"></div>
        <div class="circle-pink2"></div>
        <div class="circle-blue2"></div>
    </div>

    <div class="container">
        <?php include 'sidebar.php'; ?>
        <div class="background">
            <div class="circle-pink"></div>
            <div class="circle-blue"></div>
            <div class="circle-pink2"></div>
            <div class="circle-blue2"></div>
        </div>
        <div class="main-content">
            <h1 style="z-index: 1000;"><i class="fas fa-truck"></i> Status Update</h1>
            <div class="wrapper" style="z-index: 10000;">
                <div class="tracking-container">
                    <div class="order-details">
                        <div class="detail-row">
                            <span>Order ID:</span>
                            <span>#<?php echo $lastOrder['Order_ID'] ?></span>
                        </div>
                        <div class="detail-row">
                            <span>Customer ID:</span>
                            <span><?php echo $lastOrder['Customer_ID'] ?></span>
                        </div>
                        <div class="detail-row">
                            <span>Food Items:</span>
                            <span>
                                <ul style="list-style: none; padding: 0; margin: 0;">
                                    <li><?php echo $lastOrder['Quantity'] ?> x <?php echo $lastOrder['Food_Name'] ?></li>
                                </ul>
                            </span>
                        </div>

                        <script>
                            function updateStatus(status) {
                                // Here you can add AJAX call to update the status in database
                                console.log('Status updated to: ' + status);
                            }
                        </script>
                    </div>
                </div>

                <div class="detail-row">
                    <span>Food Items:</span>
                    <span>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <input type="hidden" name="order_id" value="<?php echo $lastOrder['Order_ID']; ?>">
                            <select name="order_status" class="form-control" id="orderStatus">
                                <option value="Preparing" <?php echo ($orderStatus['Order_Status'] == 'Preparing') ? 'selected' : ''; ?>>Preparing</option>
                                <option value="Ready" <?php echo ($orderStatus['Order_Status'] == 'Ready') ? 'selected' : ''; ?>>Ready</option>
                                <option value="Completed" <?php echo ($orderStatus['Order_Status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                            </select>
                            <button type="submit" style="
                                            padding: 8px 12px;
                                            border: none;
                                            border-radius: 4px;
                                            background-color: #4CAF50;
                                            color: white;
                                            font-size: 14px;
                                            cursor: pointer;
                                            margin-left: 10px;
                                        ">Update</button>
                        </form>
                    </span>
                </div>
                <!-- Image Section -->
                <div class="image-container">
                    <img src="../images/food2.png" alt="Food Image">
                </div>
            </div>
        </div>
    </div>
    <script>
        function updateStatus(status) {
            console.log('Status updated to: ' + status);
        }
    </script>

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

        .circle-pink {
            position: absolute;
            top: -100px;
            left: -150px;
            width: 600px;
            height: 600px;
            background-color: #8974ff;
            border-radius: 70%;
            filter: blur(50px);
            opacity: 0.8;
            animation: float 10s infinite ease-in-out;
        }

        .circle-blue {
            position: absolute;
            bottom: -200px;
            right: -200px;
            width: 600px;
            height: 600px;
            background-color: #8974ff;
            border-radius: 60%;
            filter: blur(70px);
            opacity: 0.8;
            animation: float 10s infinite ease-in-out;
        }

        .circle-pink2 {
            position: absolute;
            top: -200px;
            right: 300px;
            width: 600px;
            height: 600px;
            background-color: #ff7bfb;
            border-radius: 90%;
            filter: blur(50px);
            opacity: 0.8;
            animation: float 10s infinite ease-in-out;
        }

        .circle-blue2 {
            position: absolute;
            bottom: -400px;
            left: 60px;
            width: 600px;
            height: 600px;
            background-color: #ff7bfb;
            border-radius: 60%;
            filter: blur(70px);
            opacity: 0.8;
            animation: float 10s infinite ease-in-out;
        }
    </style>
    <script>
        function updateStatus(status) {
            console.log('Status updated to: ' + status);
        }
    </script>

</body>

</html>