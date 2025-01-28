<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main</title>
    <link rel="stylesheet" href="/public/css/style.css"> <!-- Use absolute path -->
</head>
<body>

<div class="top-bar">

        <div class="dropdown menu-dropdown">
            <button class="dropbtn">
                <img src="/public/img/menu-icon.svg" alt="Menu"> <!-- Use absolute path -->
            </button>
            <div class="dropdown-content">
                <a href="#">Function 1</a>
                <a href="#">Function 2</a>
                <a href="#">Function 3</a>
                <a href="#">Function 4</a>
                <a href="#">Function 5</a>
                <a href="#">Function 6</a>
            </div>
        </div>
        <div class="dropdown account-dropdown">
            <button class="dropbtn">
                <img src="/public/img/account-icon.svg" alt="Account"> <!-- Use absolute path -->
            </button>
            <div class="dropdown-content">
                <a href="/public/profile">Profile</a> <!-- Use absolute path -->
                <a href="/public/settings">Settings</a> <!-- Use absolute path -->
                <a href="/public/logout">Logout</a> <!-- Use absolute path -->
            </div>
        </div>
    </div>
    <div class="main-container">

        <div class="logo-container">
            <img src="/public/img/logo.svg" alt="Logo"> <!-- Use absolute path -->
        </div>
        <h2>Welcome to the Main Page</h2>
    </div>
</body>
</html>