<?php
require_once '../database.php';

class Inventory
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getProducts($search = "", $month = "", $year = "")
    {
        $conn = $this->db->getConnection();

        $sql = "SELECT 
                    Product_ID, 
                    Product_Name, 
                    Units, 
                    Status, 
                    inStock, 
                    outStock, 
                    Date 
                FROM inventory 
                WHERE 1";

        if (!empty($search)) {
            $sql .= " AND (Product_Name LIKE '%$search%' OR Units LIKE '%$search%')";
        }

        if (!empty($month)) {
            $sql .= " AND MONTH(Date) = '$month'";
        }

        if (!empty($year)) {
            $sql .= " AND YEAR(Date) = '$year'";
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function deleteProduct($productId)
    {
        $conn = $this->db->getConnection();

        $sql = "DELETE FROM inventory WHERE Product_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);

        return $stmt->execute();
    }

    public function getMonthlyReport($month, $year)
    {
        $conn = $this->db->getConnection();

        $sql = "SELECT 
                    Product_ID, 
                    Product_Name, 
                    Units, 
                    Status, 
                    inStock, 
                    outStock, 
                    Date 
                FROM inventory 
                WHERE MONTH(Date) = ? AND YEAR(Date) = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $month, $year);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getTotalProducts()
    {
        $conn = $this->db->getConnection();

        $sql = "SELECT COUNT(*) as total FROM inventory";
        $result = $conn->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'];
        } else {
            return 0;
        }
    }
}

$inventory = new Inventory();

// Handle Delete
if (isset($_POST['delete'])) {
    $productId = $_POST['product_id'];
    $inventory->deleteProduct($productId);
}

// Handle Filters
$search = $_GET['search'] ?? "";
$month = $_GET['month'] ?? "";
$year = $_GET['year'] ?? "";
$products = $inventory->getProducts($search, $month, $year);

// Get Total Products
$totalProducts = $inventory->getTotalProducts();

// Handle Monthly Report
if (isset($_GET['download_report'])) {
    $lastMonth = date('m', strtotime('first day of last month'));
    $lastYear = date('Y', strtotime('first day of last month'));
    $reportData = $inventory->getMonthlyReport($lastMonth, $lastYear);

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="monthly_report.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Product ID', 'Product Name', 'Units', 'Status', 'In Stock', 'Out Stock', 'Date']);

    if ($reportData) {
        while ($row = $reportData->fetch_assoc()) {
            fputcsv($output, $row);
        }
    }
    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .sidebar {
            width: 250px;
            background-color: #333;
            min-height: 100vh;
            padding: 20px;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
            position: relative;
            z-index: 10;
            min-height: 100vh;
        }

        .main-content .wrapper {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-bar,
        .filters,
        .total-products {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search-bar input {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 60%;
        }

        .search-bar button,
        .filters button {
            padding: 8px 15px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-bar button:hover,
        .filters button:hover {
            background-color: #2980b9;
        }

        .filters {
            gap: 10px;
        }

        .filters select {
            padding: 8px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .filters label {
            font-weight: bold;
        }

        .total-products {
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .total-products a {
            background-color: #2ecc71;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        .total-products a:hover {
            background-color: #27ae60;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        td {
            background-color: #fff;
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
        <div class="main-content">
            <div class="wrapper">
                <h1>Inventory Management</h1>

                <div class="search-bar">
                    <form method="GET" action="" style="width: 100%; display: flex; justify-content: space-between; align-items: center;">
                        <input type="text" name="search" placeholder="Search Products" value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit">Search</button>
                    </form>
                </div>

                <div class="filters">
                    <form method="GET" action="" style="width: 100%; display: flex; gap: 20px; justify-content: flex-start;">
                        <div>
                            <label for="month">Month:</label>
                            <select name="month" id="month">
                                <option value="">All</option>
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php echo ($month == $i) ? 'selected' : ''; ?>>
                                        <?php echo date('F', mktime(0, 0, 0, $i, 1)); ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div>
                            <label for="year">Year:</label>
                            <select name="year" id="year">
                                <option value="">All</option>
                                <?php for ($i = 2020; $i <= date('Y'); $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php echo ($year == $i) ? 'selected' : ''; ?>>
                                        <?php echo $i; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <button type="submit" style="background: none; border: none; cursor: pointer;">
                            <img src="search.png" alt="Search" style="width: 25px; height: 25px;">
                        </button>
                    </form>
                </div>

                <div class="total-products">
                    <strong>Total Products: <?php echo $totalProducts; ?></strong>
                    <a href="?download_report=1" class="btn btn-report">Download Last Month's Report</a>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Units</th>
                            <th>Status</th>
                            <th>In Stock</th>
                            <th>Out Stock</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($products): ?>
                            <?php while ($row = $products->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['Product_ID']; ?></td>
                                    <td><?php echo $row['Product_Name']; ?></td>
                                    <td><?php echo $row['Units']; ?></td>
                                    <td><?php echo $row['Status']; ?></td>
                                    <td><?php echo $row['inStock']; ?></td>
                                    <td><?php echo $row['outStock']; ?></td>
                                    <td><?php echo $row['Date']; ?></td>
                                    <td>
                                        <form method="POST" style="display: inline-block;">
                                            <input type="hidden" name="product_id" value="<?php echo $row['Product_ID']; ?>">
                                            <button type="submit" name="delete" class="btn btn-delete" style="background-color: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer;">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">No products found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="background">
        <div class="circle-pink"></div>
        <div class="circle-blue"></div>
        <div class="circle-pink2"></div>
        <div class="circle-blue2"></div>
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