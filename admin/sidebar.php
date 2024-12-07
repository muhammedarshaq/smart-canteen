<?php
session_start();
?>
<div class="sidebar">
    <div class="menu">
        <div class="menu-item">
            <a href="#">
                <img src="../images/house.png" alt="Home">
                <span>Home</span>
            </a>
        </div>
        <div class="menu-item">
            <a href="profile.php">
                <img src="../images/user.png" alt="My Profile">
                <span>My Profile</span>
            </a>
        </div>
        <div class="menu-item">
            <a href="order-tracking.php">
                <img src="../images/history.png" alt="History">
                <span>History</span>
            </a>
        </div>
        <div class="menu-item">
            <a href="add-food.php">
                <img src="../images/manage.png" alt="Manage">
                <span>Manage</span>
            </a>
        </div>
        <div class="menu-item">
            <a href="inventory.php">
                <img src="../images/inventory.png" alt="Inventory">
                <span>Inventory</span>
            </a>
        </div>
        <div class="menu-item">
            <a href="logout.php">
                <img src="../images/logout.png" alt="Log Out">
                <span>Log Out</span>
            </a>
        </div>
    </div>
    <div class="footer">
        <img src="../images/canteen logo.png" alt="Store">
    </div>
</div>

<style>
    .sidebar {
        width: 100px;
        /* Adjusted to fit the narrow width in the image */
        height: 100vh;
        background: #fff;
        /* White background as seen in the image */
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 10px 0;
        border-right: 1px solid #ccc;
        /* Subtle border for separation */
    }

    .menu {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .menu-item {
        margin: 20px 0;
        /* Space between icons */
    }

    .menu-item a {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: #000;
        /* Black icon and text color */
        font-size: 14px;
        /* Adjusted font size for text */
    }

    .menu-item img {
        width: 40px;
        /* Icon size */
        height: 40px;
        margin-bottom: 5px;
        /* Space between icon and text */
    }



    .footer {
        margin-top: auto;
        text-align: center;
        padding-bottom: 10px;
    }

    .footer img {
        width: 60px;
        /* Footer image size */
        height: auto;
    }
</style>