<?php
require_once '../database.php';
session_start();
class CustomerRegistration
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function registerCustomer($name, $email, $phone, $password)
    {
        $conn = $this->db->getConnection();

        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO customer (`Name`, `Email`, `Phone_Number`, `Password`) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $password);

        if ($stmt->execute()) {
            $_SESSION['email'] = $email;
            header("Location: home.php");
            return true;
        } else {
            return false;
        }
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $registration = new CustomerRegistration();

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    if ($registration->registerCustomer($name, $email, $phone, $password)) {
        echo "Customer registration successful!";
    } else {
        echo "Customer registration failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration</title>
    <style>
        /* Body and Container Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #8974FF, #FF7BFB);
        }

        .container {
            background: white;
            width: 90%;
            max-width: 400px;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
            padding: 20px;
            box-sizing: border-box;
        }

        h2 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 20px;
        }

        input {
            width: 80%;
            padding: 10px;
            margin: 10px auto;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: block;
            box-sizing: border-box;
        }

        button {
            background-color: #9fa0f4;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #7f79e0;
        }

        a {
            display: block;
            margin-top: 15px;
            color: #8974FF;
            text-decoration: none;
            font-size: 0.9rem;
        }

        a:hover {
            text-decoration: underline;
        }

        img {
            width: 100%;
            border-top: 1px solid #ddd;
            margin-top: 20px;
        }

        /* Footer Navbar */
        .footer {
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 10px;
            background-color: #f9f9f9;
            border-top: 1px solid #ddd;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }

        .footer img {
            width: 24px;
            height: 24px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="tel" name="phone" placeholder="Phone Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Create Account</button>
        </form>
        <a href="login.php">Already have an account? Login</a>
        <img src="https://images.unsplash.com/photo-1468218620578-e8d78dcda7b1?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Zm9vZCUyMHN3ZWV0c3xlbnwwfDB8MHx8fDA%3D" alt="Food Illustration">
    </div>

    <!-- Footer Navbar -->
    <div class="footer">
        <img src="../images/house.png" alt="Home">
        <img src="../images/tray.png" alt="Orders">
        <img src="../images/cart.png" alt="Cart">
        <img src="../images/user.png" alt="Profile">
    </div>
</body>

</html>