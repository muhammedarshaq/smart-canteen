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
            session_start();
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
    <title>Customer Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .registration-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            /* Adjusted for responsiveness */
            max-width: 600px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        input,
        textarea {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .menu-container {
            max-width: 100%;
            /* Adjusted for responsiveness */
            margin: 0 auto;
            padding: 20px;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .menu-item {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .menu-item h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .menu-item button {
            background-color: #2196f3;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .menu-item button:hover {
            background-color: #1976d2;
        }

        /* Common mobile responsiveness adjustments */
        @media (max-width: 768px) {
            .menu-grid {
                grid-template-columns: 1fr;
            }

            .user-registration-container form,
            .order-details {
                width: 90%;
                /* Adjust width for smaller screens */
            }

            .item-container {
                flex-direction: column;
                align-items: flex-start;
            }

            .item-container img {
                margin-bottom: 10px;
                /* Adjust spacing for stacking layout */
            }

            .categories-container {
                grid-template-columns: 1fr;
                /* Single column layout for small screens */
            }

            .home-container header input {
                width: 100%;
            }

            .order-status-timeline {
                flex-direction: column;
                align-items: center;
            }

            .status-indicator {
                margin-bottom: 10px;
                /* Adjust for vertical layout */
            }
        }

        @media (max-width: 375px) {

            .registration-container,
            .user-registration-container form,
            .order-details {
                padding: 20px;
                /* Reduce padding for very small screens */
            }

            h2 {
                font-size: 1.2em;
                /* Adjust heading size */
            }

            input,
            textarea {
                padding: 8px;
                /* Adjust input padding */
            }

            button {
                padding: 8px;
                /* Adjust button size */
            }

            .item-container img {
                width: 60px;
                height: 60px;
                /* Reduce image size for smaller screens */
            }

            .categories-container {
                gap: 10px;
                /* Reduce gap between items */
            }
        }
    </style>

</head>

<body>
    <div class="registration-container">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h2>Login</h2>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <br>
        <a href="register.php">Don't have an account? Sign Up</a>
    </div>
</body>

</html>