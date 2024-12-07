<?php
require_once '../database.php';

session_start();

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart']) || empty($_SESSION['cart'])) {
    // Redirect to cart if no items exist
    header("Location: cart.php");
    exit();
}
// Ensure that the session cart is initialized and has items
if (!isset($_SESSION['email']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

// Process order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn->begin_transaction();

        // Get user ID from the session email
        $email = $_SESSION['email'];
        $user_query = "SELECT ID FROM customer WHERE Email = ?";
        $stmt = $conn->prepare($user_query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user_result = $stmt->get_result();

        if ($user_result->num_rows === 0) {
            throw new Exception("User not found.");
        }

        $user = $user_result->fetch_assoc();
        $user_id = $user['ID'];

        // Create the order
        $order_status = "Pending";
        // Assuming food_name and quantity are from the first item in the cart (you can extend this for multiple items)
        $order_query = "INSERT INTO `order` (Customer_ID, Food_Name, Quantity, `Description`) VALUES (?, ?, ?, '')";
        $stmt = $conn->prepare($order_query);
        $stmt->bind_param("isi", $user_id, $_SESSION['cart'][0]['food_name'], $_SESSION['cart'][0]['quantity']);
        $stmt->execute();
        $order_id = $conn->insert_id;

        // Add order status entry
        $order_items_query = "INSERT INTO `order_status` (Customer_ID, Order_Status, Time_line) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($order_items_query);
        $stmt->bind_param("is", $user_id, $order_status);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        // Clear the cart after successful order
        $_SESSION['cart'] = null;

        // Redirect to order status page
        header("Location: status.php");
        exit();
    } catch (Exception $e) {
        // Rollback the transaction if something fails
        $conn->rollback();
        $_SESSION['cart'] = null;
        $error = "Order processing failed. Please try again. Error: " . $e->getMessage();
    }
}

// Calculate totals if cart is set
$subtotal = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
}
$total = $subtotal;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Smart Canteen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* ... existing css styles from cart.php ... */
        .order-summary {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .error-message {
            color: red;
            text-align: center;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <div class="cart-container">
        <h1 class="cart-title">Checkout</h1>

        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="order-summary">
            <h2>Order Summary</h2>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <div class="summary-item">
                    <span><?php echo $item['food_name']; ?> x <?php echo $item['quantity']; ?></span>
                    <span>Rs <?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                </div>
            <?php endforeach; ?>

            <div class="summary-item">
                <strong>Total:</strong>
                <strong>Rs <?php echo number_format($total, 2); ?></strong>
            </div>
        </div>

        <form method="POST" action="checkout.php">
            <button type="submit" class="checkout-btn">Confirm Order</button>
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
            <a href="cart.php" class="nav-item active">
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
<style>
    .cart-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
    }

    .cart-title {
        text-align: center;
        margin-bottom: 20px;
    }

    .checkout-btn {
        width: 100%;
        padding: 15px;
        background-color: #28a745;
        color: #fff;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
    }

    .checkout-btn:hover {
        background-color: #218838;
    }

    .bottom-nav {
        position: fixed;
        bottom: 0;
        width: 100%;
        background-color: #fff;
        box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
    }

    .nav-items {
        display: flex;
        justify-content: space-around;
        padding: 10px 0;
    }

    .nav-item {
        text-align: center;
        color: #333;
        text-decoration: none;
    }

    .nav-item.active {
        color: #007bff;
    }

    .nav-item i {
        display: block;
        font-size: 24px;
    }

    .nav-item span {
        display: block;
        font-size: 12px;
    }
</style>