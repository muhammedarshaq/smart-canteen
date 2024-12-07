<?php
require_once '../database.php';
session_start();
if (isset($_SESSION['email'])) {
    header("Location: home.php");
    exit();
}
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
            session_start();
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
    <title>Customer Registration</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .registration-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 16px;
        }

        button:hover {
            background: #45a049;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #666;
            text-decoration: none;
        }

        a:hover {
            color: #4CAF50;
        }
    </style>
</head>

<body>
    <div class="registration-container">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h2>Sign Up</h2>
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="tel" name="phone" placeholder="Phone Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Create Account</button>
        </form>
        <a href="login.php">Already have an account? Login</a>
    </div>
</body>

</html>