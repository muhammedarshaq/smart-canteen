<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Canteen Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <style>
        * {
            font-family: Arial, sans-serif;
        }

        .body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .main {
            flex: 0;
            padding: 0px;
        }

        .chef-image {
            align-items: right;
        }

        .food-images {
            display: flex;
            flex-direction: column;
            align-content: flex-start;
            flex-wrap: nowrap;
            margin-top: 0px;
            padding: 0;
        }

        .food-images img {
            width: 200px;
            height: 132px;
        }

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            z-index: -1;
        }

        /* Pink Circle */
        .circle-pink {
            position: absolute;
            top: -100px;
            left: -150px;
            width: 600px;
            height: 600px;
            background-color: #8974ff;
            border-radius: 70%;
            filter: blur(50px);
            opacity: 0.8;
            animation: float 10s infinite ease-in-out;
        }

        /* Blue Circle */
        .circle-blue {
            position: absolute;
            bottom: -200px;
            right: -200px;
            width: 600px;
            height: 600px;
            background-color: #8974ff;
            border-radius: 60%;
            filter: blur(70px);
            opacity: 0.8;
            animation: float 10s infinite ease-in-out;
        }

        /* Pink Circle */
        .circle-pink2 {
            position: absolute;
            top: -200px;
            right: 300px;
            width: 600px;
            height: 600px;
            background-color: #ff7bfb;
            border-radius: 90%;
            filter: blur(50px);
            opacity: 0.8;
            animation: float 10s infinite ease-in-out;
        }

        /* Blue Circle */
        .circle-blue2 {
            position: absolute;
            bottom: -400px;
            left: 60px;
            width: 600px;
            height: 600px;
            background-color: #ff7bfb;
            border-radius: 60%;
            filter: blur(70px);
            opacity: 0.8;
            animation: float 10s infinite ease-in-out;
        }

        .title-bar {
            background-color: rgb(255, 255, 255);
            /* White with slight transparency */
            width: 100%;
            height: 13%;
            padding: 30;
            position: fixed;
            /* Position it on top of the background */
            top: 0;
            left: 0;
            z-index: 1;
            /* Fix it to the top of the container */
            box-shadow: 0 4px 8px rgba(113, 113, 113, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content {
            max-width: 70%;
            /* Control width */
            background-color: none;
            padding: 30px;
            margin: 50px auto;
            /* Center the box horizontally */
            border-radius: 35px;
            /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Shadow for depth */
            text-align: left;
            position: relative;
            top: 100px;
        }

        h1 {
            margin-left: 10%;
            text-align: center;
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
        }

        .div-bar {
            background-color: rgb(255, 255, 255);
            /* White with slight transparency */
            width: 5%;
            height: 87%;
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
            margin-top: 150px;
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
            width: 40px;
            height: 40px;
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

        .chef-logo {
            height: 150px;
            position: absolute;
            /* Change to absolute positioning */
            top: 100px;
            /* Adjust as needed */
            right: 20px;
            /* Adjust as needed */
        }

        .canteen-image-container {
            display: flex;
            justify-content: center;
            /* Centers the image horizontally */
            align-items: center;
            /* Centers the image vertically if the container has a height */
            height: 100vh;
            /* Optional: Set the height of the container to full viewport height */
        }

        .canteen-image {
            max-width: 100%;
            /* Ensures the image is responsive */
            height: 300px;
            /* Maintains aspect ratio */
            top: 200px;
        }

        .right-side {
            position: absolute;
            /* Change to absolute positioning */
            top: 400px;
            /* Adjust as needed */
            right: 200px;
            /* Adjust as needed */
            gap: 20px;
        }

        .right-side1 {
            position: absolute;
            /* Change to absolute positioning */
            top: 460px;
            /* Adjust as needed */
            right: 200px;
            /* Adjust as needed */
            gap: 20px;
        }

        .welcome-message {
            font-size: 30px;
            color: #1d1717fb;
        }

        .login-button {
            padding: 10px 20px;
            background-color: rgb(5, 198, 44);
            color: #100404;
            border: 2px #097524;
            border-radius: 5px;
            cursor: pointer;
            right: 100px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }

        .login-button:hover {
            background-color: #097524;
            color: #ffffff;
            box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.2);
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(20px);
            }
        }

        @media (max-width: 768px) {
            .title-bar {
                height: auto;
                flex-direction: column;
                justify-content: center;
                padding: 10px;
            }

            .content {
                max-width: 70%;
                margin: 20px auto;
            }

            .right-side,
            .right-side1 {
                top: auto;
                right: auto;
                position: relative;
                text-align: center;
                margin-top: 20px;
            }

            .canteen-image {
                height: auto;
                max-width: 80%;
            }
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f0f2f5;
        }

        .container {
            text-align: left;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: left;
            align-items: center;
            margin: 0 auto;
            max-width: 800px;
        }

        .role-cards {
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        .role-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 200px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .role-card:hover {
            transform: translateY(-5px);
        }

        .role-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .role-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .role-description {
            font-size: 14px;
            color: #666;
            margin-top: 10px;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="background">
        <div class="circle-pink"></div>
        <div class="circle-blue"></div>
        <div class="circle-pink2"></div>
        <div class="circle-blue2"></div>
    </div>
    <div class="title-bar">
        <h1>Select Your Role</h1>
        <div class="search-bar">
            <input type="text" placeholder="Search Order">
            <button>🔍</button>
        </div>
    </div>
    <main class="content">

        <div class="registration">
            <div class="register-box">
                <h1>Register</h1>
                <a href="register.php?role=user"><button class="btn">Register as a user</button></a>
                <a href="register.php?role=employee"><button class="btn">Register as an employee</button></a>
                <p>Already have an account? <a href="login.php">LOGIN</a></p>
            </div>
            <div class="image-box">
                <img src="https://via.placeholder.com/400x300" alt="Pasta">
            </div>
        </div>
    </main>

    <div id="flex-container" class="div-bar">
        <ul>
            <a href="index.php"><img src="../images/house.png" id="iconhome" /></a>
            <div class="icon" data-name="My Profile"><a href="profile.php"><img src="../images/user.png" id="iconimages" /></a></div>
            <div class="icon" data-name="Order Manage"><a href="#"><img src="../images/manage.png" id="iconimages" /></a></div>
            <div class="icon" data-name="Order Histoty"><a href="order-tracking.php"><img src="../images/history.png" id="iconimages" /></a></div>
            <div class="icon" data-name="Inventory"><a href="inventory.php"><img src="../images/inventory.png" id="iconimages" /></a></div>
            <div><img src="../images/canteen logo.png" id="sclogo" /></div>
        </ul>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

<style>
    .content {
        flex: 1;
        background: linear-gradient(45deg, #f3e9ff, #ffdce5);
        padding: 20px;
    }

    .search-bar {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
    }

    .search-bar input {
        padding: 5px;
        width: 200px;
        border: 1px solid #ccc;
        border-radius: 5px 0 0 5px;
    }

    .search-bar button {
        padding: 5px;
        background: #db72db;
        color: white;
        border: none;
        border-radius: 0 5px 5px 0;
        cursor: pointer;
    }

    /* Registration Section */
    .registration {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .register-box {
        width: 45%;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
    }

    .register-box h1 {
        font-size: 2em;
        color: #6e4dd6;
        margin-bottom: 20px;
    }

    .register-box .btn {
        display: block;
        width: 80%;
        margin: 10px auto;
        padding: 10px;
        background: #db72db;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1em;
    }

    .register-box .btn:hover {
        background: #c05cbc;
    }

    .register-box p {
        margin-top: 20px;
    }

    .register-box a {
        color: #6e4dd6;
        text-decoration: none;
        font-weight: bold;
    }

    .register-box a:hover {
        text-decoration: underline;
    }

    .image-box {
        width: 45%;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .image-box img {
        width: 100%;
        height: 500px;
        display: block;
    }
</style>

</html>