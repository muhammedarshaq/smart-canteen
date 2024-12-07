
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menuId = $_POST['menu_id'];
    $foodName = $_POST['food_name'];
    $price = $_POST['price'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Add item to cart
    $_SESSION['cart'][] = array(
        'menu_id' => $menuId,
        'food_name' => $foodName,
        'price' => $price,
        'quantity' => 1
    );

    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false]);