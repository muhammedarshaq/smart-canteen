<?php
require_once '../database.php';
// Check if session exists and redirect if user is already logged in
session_start();
if (isset($_SESSION['email'])) {
    header("Location: home.php");
    exit();
}

class CustomerLogin
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function loginCustomer($email, $password)
    {
        $conn = $this->db->getConnection();

        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM customer WHERE Email = ? AND Password = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $_SESSION['email'] = $email;
            header("Location: home.php");
        } else {
            echo "Invalid email or password.";
        }
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = new CustomerLogin();

    $email = $_POST['email'];
    $password = $_POST['password'];

    $login->loginCustomer($email, $password);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>
    <style>
        /* Background Styles */
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
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        input {
            width: 80%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
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
        <h2>Login</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <a href="register.php">Don't have an account? Sign Up</a>
        <img src="../images/food1.png" alt="Food Illustration">
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