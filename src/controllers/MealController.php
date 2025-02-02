<?php

use models\Meal;
use models\Product;
use repository\MealRepository;
use utils\RequestUtils;
use utils\UserUtils;

require_once 'AppController.php';
require_once 'src/models/Meal.php';
require_once 'src/models/Product.php';
require_once 'src/repository/MealRepository.php';
require_once 'src/utils/RequestUtils.php';
require_once 'src/utils/UserUtils.php';


class MealController extends AppController {
    private $mealRepository;

    public function __construct() {
        parent::__construct();
        $this->mealRepository = new MealRepository();
    }

    public function addMeal()
    {
        header('Content-Type: application/json'); // Ensure JSON response

        if (!$this->isPost()) {
            echo json_encode(["success" => false, "message" => "Invalid request method."]);
            exit;
        }

        $data = RequestUtils::getPostData();

        // Handle JSON decoding errors
        if (!is_array($data)) {
            echo json_encode(["success" => false, "message" => "Invalid JSON data."]);
            exit;
        }

        try {
            RequestUtils::validateInput(["meal_name", "products"], $data);

            $mealName = trim($data['meal_name']);
            $products = $data['products'];

            // Check if meal name is empty or products list is invalid
            if (empty($mealName) || !is_array($products) || count($products) === 0) {
                echo json_encode(["success" => false, "message" => "Meal name and products cannot be empty."]);
                exit;
            }

            $meal = new Meal($mealName);

            foreach ($products as $productData) {
                if (!isset($productData['product_name']) || !isset($productData['product_quantity'])) {
                    echo json_encode(["success" => false, "message" => "Invalid product data."]);
                    exit;
                }

                $productName = trim($productData['product_name']);
                $productQuantity = (int) $productData['product_quantity'];

                if (empty($productName) || $productQuantity <= 0) {
                    echo json_encode(["success" => false, "message" => "Product name cannot be empty, and quantity must be greater than zero."]);
                    exit;
                }

                $product = new Product($productName);
                $meal->addProduct($product, $productQuantity);
            }

            $result = $this->mealRepository->addMeal($meal);

            if (isset($result['error'])) {
                echo json_encode(["success" => false, "message" => $result['error']]);
            } else {
                echo json_encode(["success" => true, "message" => $result['success']]);
            }
            exit;
        } catch (Exception $e) {
            echo json_encode(["success" => false, "message" => "Unexpected error: " . $e->getMessage()]);
            exit;
        }
    }


    public function editMeal()
    {
        header('Content-Type: application/json');

        if (!$this->isPost()) {
            echo json_encode(["success" => false, "message" => "Invalid request method."]);
            exit;
        }

        $data = RequestUtils::getPostData();

        if (!is_array($data)) {
            echo json_encode(["success" => false, "message" => "Invalid JSON data."]);
            exit;
        }

        try {
            RequestUtils::validateInput(["meal_id", "meal_name", "products"], $data);

            $mealId = (int) $data['meal_id'];
            $mealName = trim($data['meal_name']);
            $products = $data['products'];

            // Validate the meal data
            if ($mealId <= 0 || empty($mealName) || !is_array($products) || count($products) === 0) {
                echo json_encode(["success" => false, "message" => "Invalid meal data."]);
                exit;
            }

            // Create the meal object with the name
            $meal = new Meal($mealName);

            // Add each product to the meal
            foreach ($products as $productData) {
                if (!isset($productData['product_name']) || !isset($productData['product_quantity'])) {
                    echo json_encode(["success" => false, "message" => "Invalid product data."]);
                    exit;
                }

                $productName = trim($productData['product_name']);
                $productQuantity = (int) $productData['product_quantity'];

                // Validate product name and quantity
                if (empty($productName) || $productQuantity <= 0) {
                    echo json_encode(["success" => false, "message" => "Product name cannot be empty, and quantity must be greater than zero."]);
                    exit;
                }

                $product = new Product($productName);
                $meal->addProduct($product, $productQuantity);
            }

            // Update the meal in the database
            $result = $this->mealRepository->updateMeal($meal, $mealId);

            if (isset($result['error'])) {
                echo json_encode(["success" => false, "message" => $result['error']]);
            } else {
                echo json_encode(["success" => true, "message" => "Meal updated successfully."]);
            }

            exit;
        } catch (Exception $e) {
            echo json_encode(["success" => false, "message" => "Unexpected error: " . $e->getMessage()]);
            exit;
        }
    }



    public function showAllMeals()
    {
        $allMeals = $this->mealRepository->getMeals();
        $this->render('main', ['allMeals' => $allMeals]);

    }






}