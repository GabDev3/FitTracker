<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <script type="text/javascript" src="/public/js/mainScript.js" defer></script>
</head>
<body>

<div class="top-bar">
    <div class="dropdown account-dropdown">
        <button class="dropbtn">
            <img src="/public/img/account-icon.svg" alt="Account">
        </button>
        <div class="dropdown-content">
            <!-- show manage accounts option for an admin -->
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 2) : ?>
                <a href="/manage-users">Manage User Accounts</a>
            <?php endif; ?>

            <a href="/logout">Logout</a>
        </div>
    </div>
</div>

<div class="main-container">
    <div class="logo-container">
        <img src="/public/img/logo.svg" alt="Logo">
    </div>

    <button class="add-meal-button" onclick="toggleForm()">Add Meal</button>

    <!-- Show All Meals Button -->
    <button class="show-meals-button" onclick="window.location.href='/show-all-meals'">Show All Meals</button>

    <!-- Pop-up container for displaying all meals -->
    <div class="popup-form" id="mealsPopup" style="<?php echo isset($allMeals) && !empty($allMeals) ? 'display: block;' : 'display: none;'; ?>">
        <h2>All Meals</h2>
        <div class="meals-list">
            <?php if (isset($allMeals) && !empty($allMeals)) : ?>
                <?php foreach ($allMeals as $meal) : ?>
                    <div class="meal-item">
                        <button class="meal-name" onclick="toggleMealDetails(<?php echo $meal->getId(); ?>)">
                            <?php echo htmlspecialchars($meal->getName()); ?>
                        </button>
                        <div id="meal-details-<?php echo $meal->getId(); ?>" class="meal-details" style="display: none;">
                            <p>Created at: <?php echo htmlspecialchars($meal->getCreatedAt()); ?></p>
                            <h4>Nutritional Information:</h4>
                            <ul>
                                <?php $nutrients = $meal->calculateNutrients(); ?>
                                <li>Carbohydrates: <?php echo round($nutrients['carbohydrates'], 2); ?>g</li>
                                <li>Fats: <?php echo round($nutrients['fats'], 2); ?>g</li>
                                <li>Protein: <?php echo round($nutrients['protein'], 2); ?>g</li>
                                <li>Fibre: <?php echo round($nutrients['fibre'], 2); ?>g</li>
                                <li>Calories: <?php echo round($nutrients['kcal'], 2); ?> kcal</li>
                            </ul>

                            <!-- Display the list of products for this meal -->
                            <h4>Products:</h4>
                            <ul>
                                <?php foreach ($meal->getProducts() as $productDetails) : ?>
                                    <li>
                                        <?php echo htmlspecialchars($productDetails['product']->getName()); ?> -
                                        <?php echo $productDetails['quantity']; ?>g
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                            <button onclick="openEditMealPopup(<?php echo $meal->getId(); ?>, '<?php echo htmlspecialchars($meal->getName()); ?>')">Edit Meal</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No meals found.</p>
            <?php endif; ?>
        </div>
        <button onclick="toggleMealsPopup()">Close</button>
    </div>






    <!-- Pop-up form for adding a meal -->
    <div class="popup-form" id="mealForm" style="<?php echo isset($messages) ? 'display: block;' : 'display: none;'; ?>">
        <h2>Add Meal</h2>

        <!-- Display error or success messages -->
        <?php if (isset($messages)) : ?>
            <?php foreach ($messages as $message) : ?>
                <div class="error-message">
                    <?php echo $message; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>


        <form class="add-meal-form">
            <input type="text" name="meal_name" placeholder="Meal Name" required>
            <input type="text" name="product_name" placeholder="Product Name" required>
            <input type="number" name="product_quantity" placeholder="Product Quantity" step="1" required>
            <button type="button" class="add-product-btn">Add Product to Meal</button>
            <ul id="product-list"></ul> <!-- This will show added products -->
            <button type="submit" class="save-meal-btn">Save Meal</button>
        </form>



        <button onclick="toggleForm()">Close</button>
    </div>
</div>

<!-- Edit Meal Pop-up form -->
<div class="popup-form" id="editMealForm" style="display: none;">
    <h2>Edit Meal</h2>
    <form class="edit-meal-form">
        <input type="hidden" name="meal_id">
        <p id="edit-meal-name"></p>
        <input type="text" name="product_name" placeholder="Product Name" required>
        <input type="number" name="product_quantity" placeholder="Product Quantity" step="1" required>
        <button type="button" class="add-product-btn">Add Product to Meal</button>
        <ul id="edit-product-list"></ul> <!-- List of added products -->
        <button type="submit" class="save-edit-meal-btn">Save Changes</button>
    </form>
    <button onclick="toggleEditMealForm()">Close</button>
</div>



</body>
</html>
