<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <div class="login-container">
        <form class="login-form" action="login" method="POST">
            <div class="logo-container">
                <img src="/public/img/logo.svg" alt="Logo">
            </div>
            <h2>Login</h2>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="login-button">Login</button>
            <div class="error-message">
                <?php
                    if(isset($messages)){
                        foreach ($messages as $message) {
                            echo $message;
                        }
                    }
                ?>

            <p class="register-link">Don't have an account? <a href="/register">Register here</a></p>
        </form>
    </div>
</body>
</html>