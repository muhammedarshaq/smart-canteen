<?php
require_once '../database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['name'];
    $phone = $_POST['phone'];

    $db = new Database();
    $conn = $db->getConnection();

    $sql = "UPDATE employee SET `Name` = ?, `Phone Number` = ? WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $fullname,  $phone, $_SESSION['email']);

    if ($stmt->execute()) {
        header("Location: profile.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
