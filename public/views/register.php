<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <div class="main-container"> <!-- Changed from register-container to main-container -->
        <form class="register-form" action="/register" method="POST"> <!-- Use absolute path -->
            <div class="logo-container">
                <img src="/public/img/logo.svg" alt="Logo"> <!-- Use absolute path -->
            </div>
            <h2>Register</h2>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="register-button">Register</button>
            <p class="login-link">Already have an account? <a href="/login">Login here</a></p> <!-- Use absolute path -->
        </form>
    </div>
</body>
</html>