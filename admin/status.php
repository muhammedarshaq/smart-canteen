<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Management - Smart Canteen Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .status-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .status-title {
            font-size: 24px;
            color: #333;
        }

        .status-actions {
            display: flex;
            gap: 10px;
        }

        .add-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .status-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .status-table th,
        .status-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .status-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }

        .edit-btn {
            background-color: #ffc107;
            color: #000;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php
        require_once '../database.php';
        // require_once '../classes/Database.php'; // Include the Database class
        // Check connection

        ?>
        <?php include 'sidebar.php'; ?>
        <div class="main-content">
            <div class="status-header">
                <h1 class="status-title">Status Management</h1>
                <div class="status-actions">
                    <!-- <a href="a" class="add-btn">
                        <i class="fas fa-plus"></i> Add New Status
                    </a> -->
                </div>
            </div>

            <table class="status-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Status Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM order_status ORDER BY id ASC";
                    $db = new Database();
                    $conn = $db->getConnection();
                    $result = mysqli_query($conn, $query);

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['status_name'] . "</td>";
                        echo "<td>" . $row['description'] . "</td>";
                        echo "<td>
                                <button class='action-btn edit-btn'><i class='fas fa-edit'></i></button>
                                <button class='action-btn delete-btn'><i class='fas fa-trash'></i></button>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
</div>