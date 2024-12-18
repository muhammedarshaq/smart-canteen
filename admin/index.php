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
            max-width: 20%;
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
            background-color: red;
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
                max-width: 90%;
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
    </div>
    <img src="../images/chefimage.png" alt="Chef Logo" class="chef-logo">
    <div class="canteen-image-container">
        <img src="../images/welcomecanteen.png" alt="Canteen" class="canteen-image">
    </div>
    <div class="right-side">
        <div class="welcome-message">HI WELCOME!</div>
    </div>
    <div class="right-side1">
        <a href="login.php"><button class="login-button">Login</button></a>
    </div>

    <div id="flex-container" class="div-bar">
        <ul>
            <div class="icon" data-name="Home"><a href="#home"><img src="../images/house.png" id="iconhome" /></a></div>
            <div class="icon" data-name="My Profile"><a href="#about"><img src="../images/user.png" id="iconimages" /></a></div>
            <div class="icon" data-name="Order Manage"><a href="#about"><img src="../images/manage.png" id="iconimages" /></a></div>
            <div class="icon" data-name="Order Histoty"><a href="#about"><img src="../images/history.png" id="iconimages" /></a></div>
            <div><a href="#about"><img src="../images/logout.png" id="iconlogout" /></a><label>Logout</label></div>
            <div><img src="../images/canteen logo.png" id="sclogo" /></div>
        </ul>
        <div class="main">
            <div class="food-images">
                <img src="../images/food1.png" alt="Food 1">
                <img src="../images/food2.png" alt="Food 2">
                <img src="../images/food3.png" alt="Food 3">
                <img src="../images/food4.png" alt="Food 4">
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>