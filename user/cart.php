<?php
require_once '../database.php';

session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle quantity updates
if (isset($_POST['update_quantity'])) {
    $menu_id = $_POST['menu_id'];
    $quantity = $_POST['quantity'];

    if ($quantity > 0) {
        $_SESSION['cart'][$menu_id]['quantity'] = $quantity;
    } else {
        unset($_SESSION['cart'][$menu_id]);
    }
    header("Location: cart.php");
    exit();
}

// Calculate totals
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$total = $subtotal;

class Food
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getFoodByMenuId($menu_id)
    {
        $conn = $this->db->getConnection();
        $sql = "SELECT * FROM menu WHERE Menu_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $menu_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Smart Canteen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            padding-bottom: 70px;
            /* Add this */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        .cart-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 20px;
        }

        .cart-title {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .cart-items {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .item-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
        }

        .item-details {
            flex: 1;
            margin-left: 20px;
        }

        .item-name {
            font-size: 18px;
            margin-bottom: 5px;
            color: #333;
        }

        .item-price {
            font-size: 16px;
            color: #666;
        }

        .item-quantity {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }

        .quantity-btn {
            background: #f0f0f0;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 16px;
        }

        .quantity-input {
            width: 50px;
            text-align: center;
            margin: 0 10px;
            padding: 5px;
            border: 1px solid #ddd;
        }

        .remove-btn {
            background: #ff4444;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
        }

        .cart-summary {
            margin-top: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .checkout-btn {
            background: #4caf50;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            margin-top: 20px;
        }

        .checkout-btn:hover {
            background: #45a049;
        }

        .empty-cart {
            text-align: center;
            padding: 40px;
            color: #666;
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
    <div class="cart-container">
        <h1 class="cart-title">Your Cart</h1>

        <div class="cart-items">
            <?php if (empty($_SESSION['cart'])): ?>
                <div class="empty-cart">
                    <p>Your cart is empty</p>
                </div>
            <?php else: ?>
                <?php foreach ($_SESSION['cart'] as $menu_id => $item): ?>
                    <div class="cart-item">
                        <?php
                        $food = new Food();
                        $foodd = $food->getFoodByMenuId($item['menu_id']);
                        ?>
                        <img src="<?php echo $foodd['Image']; ?>" alt="<?php echo $foodd['Food_Name']; ?>" class="item-image">
                        <div class="item-details">
                            <h3 class="item-name"><?php echo $foodd['Food_Name']; ?></h3>
                            <p class="item-price">Rs <?php echo number_format($foodd['Price'], 2); ?></p>
                            <form method="POST" class="item-quantity">
                                <input type="hidden" name="menu_id" value="<?php echo $menu_id; ?>">
                                <button type="button" class="quantity-btn" onclick="updateQuantity(this, -1)">-</button>
                                <input type="number" name="quantity" class="quantity-input" value="<?php echo $item['quantity']; ?>" min="0">
                                <button type="button" class="quantity-btn" onclick="updateQuantity(this, 1)">+</button>
                                <input type="submit" name="update_quantity" value="Update" class="remove-btn">
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="cart-summary">
            <div class="summary-item">
                <span>Subtotal:</span>
                <span>Rs <?php echo number_format($subtotal, 2); ?></span>
            </div>
            <div class="summary-item">
                <strong>Total:</strong>
                <strong>Rs <?php echo number_format($total, 2); ?></strong>
            </div>
            <?php if (!empty($_SESSION['cart'])): ?>
                <button class="checkout-btn" onclick="window.location.href='checkout.php'">Proceed to Checkout</button>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function updateQuantity(btn, change) {
            const input = btn.parentElement.querySelector('input[name="quantity"]');
            const newVal = parseInt(input.value) + change;
            if (newVal >= 0) {
                input.value = newVal;
            }
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
            <a href="cart.php" class="nav-item active" style="color: #d63384;">
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