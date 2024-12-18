<?php
require_once '../database.php';
session_start();

class EmployeeLogin
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function loginEmployee($email, $password)
    {
        $conn = $this->db->getConnection();

        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM employee WHERE Email = ? AND Password = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = new EmployeeLogin();

    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($login->loginEmployee($email, $password)) {
        session_start();
        $_SESSION['email'] = $email;
        header('Location: profile.php');
        exit();
        // echo "Employee login successful!";
    } else {
        echo "Employee login failed.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://images.unsplash.com/photo-1702742322469-36315505728f?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8N3x8ZGVzc2VydHN8ZW58MHx8MHx8fDA%3D');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        h1 {
            margin-left: 10%;
            text-align: center;
        }

        .search-bar {
            width: 250px;
            left: 200%;
            transform: translate(-90%, 70%);
        }

        .search-bar input {
            width: 90%;
            padding: 8px;
            padding-right: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-bar img {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 25px;
        }

        .products-btn {
            left: 60%;
            top: 30%;
            transform: translate(145%, -70%);
            background-color: #e8a6ff;
            border: 2px solid #b641e4;
            color: #322039;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .add-btn {
            right: 60%;
            top: 30%;
            transform: translate(360%, -40%);
            background-color: #e8a6ff;
            border: 2px solid #b641e4;
            color: #322039;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        #total-p {
            right: 50%;
            top: 50%;
            transform: translate(40%, 140%);
        }

        .total {
            border: hidden;
        }

        .div-bar {
            background-color: rgb(255, 255, 255);
            /* White with slight transparency */
            width: 5%;
            height: 100%;
            padding-right: 13px;
            margin-bottom: 0;
            position: fixed;
            /* Position it on top of the background */
            bottom: 0;
            left: 0;
            /* Fix it to the top of the container */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: start;
        }

        #iconhome {
            width: 35px;
            height: 35px;
            margin: 2px auto;
            top: 2px;
            left: 50%;
            transform: translate(-50%, 20%);
        }

        #iconimages {
            width: 35px;
            height: 35px;
            margin: 2px auto;
            left: 50%;
            transform: translate(-50%, 20%);
        }

        #iconlogout {
            width: 35px;
            height: 35px;
            margin-top: 180px;
            left: 50%;
            transform: translate(-50%, -10%);
        }

        #sclogo {
            width: 94px;
            height: 70px;
            position: absolute;
            bottom: 7%;
            left: 50%;
            transform: translate(-50%, 20%);
        }

        label {
            text-align: center;
            margin-left: -20px;
            color: #060606;
            font-size: 15px;
            font-weight: bold;
        }

        a {
            color: #060606;
            text-decoration: none;
            text-align: center;
            left: 50%;
            transform: translate(-50%, 20%);
            font-size: 15px;
            font-weight: bold;
        }

        a:hover {
            color: rgb(104, 95, 95);
            text-decoration: none;
        }

        a:active {
            color: #353131;
            text-decoration: none;
        }

        .deleteicon {
            width: 50px;
            height: 50px;
            margin-left: 250px;
            margin-bottom: 20px;
        }

        input[type="checkbox"] {
            transform: scale(2);
            /* Increase checkbox size */
        }

        .icon {
            position: relative;
            display: inline-block;
            margin: 5px;
        }

        .icon img {
            width: 30px;
            height: 30px;
            cursor: pointer;
        }

        .icon::after {
            content: attr(data-name);
            /* Get the name from the data-name attribute */
            position: absolute;
            bottom: 80%;
            /* Position the tooltip above the icon */
            left: 100%;
            transform: translateX(-80%);
            background-color: #333;
            /* Dark background for the tooltip */
            color: #fff;
            padding: 5px 8px;
            border-radius: 4px;
            white-space: nowrap;
            opacity: 0;
            /* Initially hidden */
            visibility: hidden;
            transition: opacity 0.2s ease;
            font-size: 12px;
            z-index: 10;
        }

        .icon:hover::after {
            opacity: 1;
            /* Show the tooltip */
            visibility: visible;
        }

        /* Confirmation Dialog */
        .confirmation-dialog {
            width: 400px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
            display: none;
        }

        .confirmation-dialog img {
            width: 40px;
            margin-bottom: 10px;
        }

        .confirmation-dialog p {
            font-size: 18px;
            margin: 10px 0;
        }

        .confirmation-dialog button {
            padding: 10px 25px;
            margin: 5px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .confirmation-dialog .no-btn {
            background-color: #5cb0fe;
            color: white;
            float: left;
        }

        .confirmation-dialog .yes-btn {
            background-color: #ff1515;
            color: white;
            float: right;
        }

        /* Overlay for confirmation dialog */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.4);
            z-index: 5;
        }

        .status-btn {
            padding: 10px 25px;
            margin: 5px 0px;
            color: white;
            border-radius: 5px;
            font-weight: bold;
        }

        #low {
            background-color: #f8c030;
        }

        #out {
            background-color: #ff4040;
        }

        #good {
            background-color: #08a217;
        }

        /* Style the container */
        .number-input {
            display: flex;
            margin: -15px 24px;
            align-items: center;
            width: 5px;
        }

        /* Style the buttons */
        .number-input button {
            color: rgba(27, 27, 27, 0.697);
            background-color: transparent;
            border: none;
            padding: 0px 10px;
            cursor: pointer;
            font-weight: bold;
            font-size: 20px;
        }

        /* Style the number input */
        .number-input input[type="number"] {
            width: 50px;
            text-align: center;
            border: 1px solid #ccc;
            font-size: 1em;
            padding: 0.2em;
        }

        .content {
            max-width: 40%;
            /* Control width */
            background-color: rgba(255,
                    255,
                    255,
                    0.8);
            /* White background with slight transparency */
            padding: 20px;
            margin: 50px auto;
            /* Center the box horizontally */
            border-radius: 20px;
            /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Shadow for depth */
            text-align: left;
            position: relative;
            top: 100px;
        }

        .content-box h2 {
            color: #333;
            margin-top: 0;
        }

        .content-box p {
            color: #555;
            font-size: 1rem;
        }

        #p1 {
            width: 80%;
            border: 1px solid #ccc;
            border-style: ridge;
            border-radius: 5px;
            padding: 10px;
            margin: auto;
        }

        /* Label Styling */
        label {
            /* display: block; */
            margin-bottom: 5px;
            padding-left: 35px;
            font-size: 1rem;
            color: #3b3b3b;
            font-weight: bold;
            font-family: serif;
            text-align: left;
        }

        .btn {
            padding: 12px 20px;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 4px;
            font-family: serif;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border: none;
        }

        .login {
            background-color: #e951e7;
            /* Blue background */
            color: #ffffff;
            margin: 1% 25% 3% 0%;
        }

        .login:hover {
            background-color: #662265;
            /* Darker blue on hover */
        }

        .login:active {
            background-color: #f09aef;
            /* Even darker on click */
        }

        /* Register Button Style */
        .register {
            background-color: #32a5f7;
            /* Green background */
            color: #ffffff;
            margin: 1% 0% 3% 25%;
        }

        .register:hover {
            background-color: #305772;
            /* Darker green on hover */
        }

        .register:active {
            background-color: #7dbdeb;
            /* Even darker on click */
        }

        #flex-container {
            vertical-align: middle;
            /* Full viewport height */
        }

        .iconimages {
            width: 55%;
            height: 55%;
            margin-left: auto;
            margin-right: 30px;
            margin-top: 20px;
        }

        .iconlogout {
            width: 55%;
            height: 55%;
            margin-left: auto;
            margin-right: 30px;
            margin-top: 250px;
        }

        #sclogo {
            width: 94px;
            height: 70px;
            position: absolute;
            bottom: 7%;
            left: 50%;
            transform: translate(-50%, 20%);
        }

        a {
            color: #060606;
            text-decoration: none;
            text-align: center;
            font-size: 15px;
            font-weight: bold;
        }

        a:hover {
            color: rgb(104, 95, 95);
            text-decoration: none;
        }

        a:active {
            color: #353131;
            text-decoration: none;
        }

        label {
            font-size: 16px;
            /* Increased label font size */
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input,
        textarea {
            width: 50%;
            padding: 10px;
            font-size: 12px;
            border: 2px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s, background-color 0.3s;
        }

        input:focus,
        textarea:focus {
            border-color: #007bff;
            background-color: #eaf4ff;
            outline: none;
        }
    </style>

</head>

<body>

    <div id="flex-container" class="div-bar">
        <ul>
            <div class="icon" data-name="Home"><a href="index.php"><img src="../images/house.png" id="iconhome" /></a></div>
            <div class="icon" data-name="My Profile"><a href="profile.php"><img src="../images/user.png" id="iconimages" /></a></div>
            <div class="icon" data-name="Order Manage"><a href="#about"><img src="../images/manage.png" id="iconimages" /></a></div>
            <div class="icon" data-name="Order Histoty"><a href="order-tracking.php"><img src="../images/history.png" id="iconimages" /></a></div>
            <div class="icon" data-name="Inventory"><a href="inventory.php"><img src="../images/inventory.png" id="iconimages" /></a></div>
            <div><a href="#about"></div>
            <div><img src="../images/canteen logo.png" id="sclogo" /></div>
        </ul>
    </div>

    <div class="content">
        <div class="employee-login-container">
            <h1>Employee Login</h1>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="email">E-mail Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email address" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter a secure password" required>
                </div>
                <div class="button-group">
                    <button class="btn cancel" type="reset">Cancel</button>
                    <button class="btn login" type="submit">Login</button>
                </div>
            </form>
            <div class="register-link">
                <p>Don't have an account? <a href="../admin/role-selection.php" style="color: purple;">Register</a></p>
            </div>
        </div>
    </div>
</body>

</html>