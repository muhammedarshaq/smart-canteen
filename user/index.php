<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Home</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #ffcce0;
            font-family: Arial, sans-serif;
            overflow: hidden;
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

        .circle-pink,
        .circle-blue,
        .circle-pink2,
        .circle-blue2 {
            position: absolute;
            width: 40vmin;
            height: 40vmin;
            border-radius: 50%;
            filter: blur(50px);
            opacity: 0.8;
        }

        .circle-pink {
            top: 5%;
            left: 10%;
            background-color: #8974FF;
        }

        .circle-blue {
            bottom: 5%;
            right: 10%;
            background-color: #8974FF;
        }

        .circle-pink2 {
            top: 10%;
            right: 25%;
            background-color: #FF7BFB;
        }

        .circle-blue2 {
            bottom: 10%;
            left: 25%;
            background-color: #FF7BFB;
        }

        .content {
            text-align: center;
            z-index: 1;
            padding: 20px;
        }

        img {
            max-width: 70%;
            height: auto;
            margin-bottom: 15px;
        }

        .centered-text {
            font-style: italic;
            font-size: 1.5rem;
            color: #f01da6;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .swap-button {
            position: fixed;
            bottom: 10%;
            right: 10%;
            background-color: red;
            color: white;
            padding: 15px 20px;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 10;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .swap-button:hover {
            background-color: #ff4d4d;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            img {
                max-width: 90%;
            }

            .centered-text {
                font-size: 1.2rem;
            }

            .swap-button {
                font-size: 0.9rem;
                padding: 12px 18px;
                bottom: 15px;
                right: 15px;
            }

            .circle-pink,
            .circle-blue,
            .circle-pink2,
            .circle-blue2 {
                width: 30vmin;
                height: 30vmin;
            }

            .circle-pink {
                top: 10%;
                left: 5%;
            }

            .circle-blue {
                bottom: 10%;
                right: 5%;
            }

            .circle-pink2 {
                top: 15%;
                right: 15%;
            }

            .circle-blue2 {
                bottom: 15%;
                left: 15%;
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

    <div class="content">
        <img src="icons/Home.png" alt="Home">
        <div class="centered-text">
            Bringing You Convenient & Delicious<br>
            Meals at Your Fingertips
        </div>
    </div>

    <button class="swap-button"
        onclick="window.location.href=' User_Login.php';"
        onmouseover="this.style.backgroundColor='#ff4d4d'"
        onmouseout="this.style.backgroundColor='red'">
        Get Start
    </button>
</body>

</html>