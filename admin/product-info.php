<?php
// include '../includes/config.php';

// Check if product_id is provided in the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch product details from the database
    $query = "SELECT p.*, c.category_name 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE p.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Information</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Product Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <img src="../uploads/<?php echo $product['image']; ?>" class="img-fluid rounded" alt="Product Image">
                    </div>
                    <div class="col-md-8">
                        <table class="table">
                            <tr>
                                <th>Product Name:</th>
                                <td><?php echo $product['name']; ?></td>
                            </tr>
                            <tr>
                                <th>Category:</th>
                                <td><?php echo $product['category_name']; ?></td>
                            </tr>
                            <tr>
                                <th>Price:</th>
                                <td>$<?php echo number_format($product['price'], 2); ?></td>
                            </tr>
                            <tr>
                                <th>Description:</th>
                                <td><?php echo $product['description']; ?></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <?php if ($product['status'] == 1): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="products.php" class="btn btn-secondary">Back to Products</a>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
</div>