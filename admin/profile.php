<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #333;
            min-height: 100vh;
            padding: 20px;
            color: white;
        }

        .sidebar h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin-bottom: 15px;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px;
        }

        .sidebar ul li a:hover {
            background-color: #444;
            border-radius: 5px;
        }

        .sidebar ul li a i {
            margin-right: 10px;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        .profile-form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
        }

        .profile-form h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn-update {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-update:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <?php
        require_once '../database.php';
        class Profile
        {
            private $db;

            public function __construct()
            {
                $this->db = new Database();
            }

            public function getUserByEmail($mail)
            {
                $conn = $this->db->getConnection();
                $sql = "SELECT * FROM employee WHERE Email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $mail);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    return $result->fetch_assoc();
                } else {
                    return false;
                }
            }
        }

        $profile = new Profile();
        $user = $profile->getUserByEmail($_SESSION['email']);
        ?>
        <div class="background">
            <div class="circle-pink"></div>
            <div class="circle-blue"></div>
            <div class="circle-pink2"></div>
            <div class="circle-blue2"></div>
        </div>
        <div class="main-content">
            <div class="profile-form">
                <h2>Profile Settings</h2>
                <form action="update-profile.php" method="POST">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" value="<?php echo $user["Name"] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo $user["Phone Number"] ?>" required>
                    </div>
                    <button type="submit" class="btn-update">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</body>
<style>
    .background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        overflow: hidden;
        z-index: -1;
        pointer-events: none;
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
</style>

</html>