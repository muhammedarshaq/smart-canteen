<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        /* Body and Container Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
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
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Header Section */
        .header {
            padding: 20px;
            background: white;
            color: black;
            font-size: 1.5rem;
            font-weight: bold;
        }

        /* Button Styles */
        .button-container {
            margin: 20px;
        }

        .button {
            display: block;
            width: 80%;
            margin: 10px auto;
            padding: 10px 0;
            border: none;
            border-radius: 8px;
            background: #9fa0f4;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        /* Link Styles */
        .link {
            color: #8974FF;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .link:hover {
            text-decoration: underline;
        }

        /* Image Styles */
        .image-container {
            margin-top: 20px;
            overflow: hidden;
        }

        .image-container img {
            width: 100%;
            border-top: 1px solid #ddd;
        }

        /* Footer Menu */
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

        @media (max-width: 768px) {
            body {
                height: auto;
                margin-bottom: 60px;
                /* For the fixed footer space */
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">Register</div>
        <hr>
        <!-- Button Section -->
        <div class="button-container">
            <a href="register.php" class="button">Register as a User</a>
            <a href="register.php" class="button">Register as an Employee</a>
        </div>

        <!-- Login Link -->
        <div>
            <a href="login.php" class="link">Already have an account? Sign In</a>
        </div>

        <!-- Image Section -->
        <div class="image-container">
            <img src="https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8N3x8Zm9vZHxlbnwwfHwwfHx8MA%3D%3D" alt="Food Items">
        </div>
    </div>

    <!-- Footer Menu -->
    <div class="footer">
        <img src="../images/house.png" alt="Home">
        <img src="../images/tray.png" alt="Orders">
        <img src="../images/cart.png" alt="Cart">
        <img src="../images/user.png" alt="Profile">
    </div>
</body>

</html>