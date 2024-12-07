<?php
require_once '../database.php';
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $db = new Database();
    $conn = $db->getConnection();

    $sql = "UPDATE customer SET Name = ?, Email = ?, Phone_Number = ? WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $fullname, $email, $phone, $_SESSION['email']);

    if ($stmt->execute()) {
        $_SESSION['email'] = $email; // Update session email if changed
        header("Location: profile.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
