<?php

require 'Routing.php';


session_start();

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);


Routing::get('', 'DefaultController');
//Routing::get('main', 'DefaultController');

Routing::get('main', 'DefaultController', 'main'); // Route to main view

Routing::get('register', 'SecurityController', 'register');   // Display register form
Routing::post('register', 'SecurityController', 'register');  // Handle register form submission
Routing::get('login', 'SecurityController', 'login');         // Display login form
Routing::post('login', 'SecurityController', 'login');        // Handle login form submission

Routing::post('add-meal', 'MealController', 'addMeal'); // Add this for form submission
Routing::get('show-all-meals', 'MealController', 'showAllMeals'); // Add this for showing all meals

Routing::get('logout', 'SecurityController', 'logout'); // Logout route

Routing::get('manage-users', 'SecurityController', 'manageUsers');

Routing::post('delete-user', 'SecurityController', 'deleteUser');  // Handle delete user request

Routing::post('edit-meal', 'MealController', 'editMeal');

Routing::run($path);
?>
