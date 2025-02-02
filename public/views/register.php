<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <script type="text/javascript" src="./public/js/script.js" defer></script>
</head>
<body>
<div class="main-container">
    <form class="register-form" action="/register" method="POST"> <!-- Use absolute path -->
        <div class="logo-container">
            <img src="/public/img/logo.svg" alt="Logo"> <!-- Use absolute path -->
        </div>
        <h2>Register</h2>

        <!-- Display any messages passed from the controller (validation errors or success) -->
        <?php if (isset($messages)) : ?>
            <div class="error-messages">
                <?php foreach ($messages as $message) : ?>
                    <p class="error"><?php echo htmlspecialchars($message); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" placeholder="Enter your email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>

        <div class="form-group">
            <label for="confirmedPassword">Confirm Password</label>
            <input type="password" id="confirmedPassword" name="confirmedPassword" placeholder="Confirm your password" required>
        </div>

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="surname">Surname</label>
            <input type="text" id="surname" name="surname" placeholder="Enter your surname" required value="<?php echo isset($_POST['surname']) ? htmlspecialchars($_POST['surname']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" placeholder="Enter your phone number" required value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
        </div>

        <button type="submit" class="register-button">Register</button>

        <p class="login-link">Already have an account? <a href="/login">Login here</a></p> <!-- Use absolute path -->
    </form>
</div>
</body>
</html>
