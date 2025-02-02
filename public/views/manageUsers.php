<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <script type="text/javascript" src="/public/js/adminScripts.js" defer></script>
</head>
<body>

<div class="top-bar">
    <div class="dropdown account-dropdown">
        <button class="dropbtn">
            <img src="/public/img/account-icon.svg" alt="Account">
        </button>
        <div class="dropdown-content">
            <a href="/logout">Logout</a>
            <!-- Added 'Go back to main' link here -->
            <a href="/main">Go back to main</a>
        </div>
    </div>
</div>

<div class="main-container">
    <h2>Manage User Accounts</h2>

    <!-- Display list of users -->
    <div class="users-list">
        <?php if (isset($users) && !empty($users)) : ?>
            <table class="users-table">
                <thead>
                <tr>
                    <th>User ID</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Phone Number</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['surname']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                            <!-- Placeholder for the delete button -->
                            <button class="delete-btn" onclick="confirmDelete(<?php echo $user['user_id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No users found.</p>
        <?php endif; ?>
    </div>
</div>

</body>

</html>
