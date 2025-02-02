<?php

namespace repository;

require_once __DIR__ . '/Repository.php';
require_once __DIR__ . '/../models/Meal.php';

use DateTime;
use Exception;
use models\Meal;
use models\Product;

class MealRepository extends Repository
{
    public function getMeal($id): ?Meal
    {
        $statement = $this->database->connect()->prepare(
            "SELECT * FROM meals m
            JOIN meal_products mp ON m.id = mp.meal_id
            JOIN products p ON p.id = mp.product_id
            WHERE m.id = :id"
        );
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($rows)) {
            return null;
        }

        // Create the Meal object
        $mealData = $rows[0];
        $meal = new Meal(
            $mealData['name'],
            $mealData['created_at'],
            $mealData['id']
        );

        // Add products to the meal
        foreach ($rows as $row) {
            $product = new Product(
                $row['name'],
                $row['carbohydrates'],
                $row['fats'],
                $row['protein'],
                $row['fibre'],
                $row['kcal'],
                $row['product_id']
            );
            $meal->addProduct($product, $row['quantity']);
        }

        return $meal;
    }

    public function addMeal(Meal $meal): array
    {
        $database = $this->database->connect();

        // Check if a meal with the same name already exists
        $stmt = $database->prepare('SELECT COUNT(*) FROM meals WHERE name = ?');
        $stmt->execute([$meal->getName()]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            return ['error' => 'A meal with this name already exists.'];
        }

        // Proceed with inserting the new meal
        $date = new DateTime();

        $stmt = $database->prepare('INSERT INTO meals (name, created_at) VALUES (?, ?)');
        $stmt->execute([$meal->getName(), $date->format('Y-m-d H:i:s')]);

        $mealId = $database->lastInsertId();

        foreach ($meal->getProducts() as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'];

            $stmt = $database->prepare('
            INSERT INTO products (name, carbohydrates, fats, protein, fibre, kcal)
            VALUES (?, ?, ?, ?, ?, ?)
            ON CONFLICT (name) DO NOTHING
        ');
            $stmt->execute([
                $product->getName(),
                $product->getCarbohydrates(),
                $product->getFats(),
                $product->getProtein(),
                $product->getFibre(),
                $product->getKcal()
            ]);

            $stmt = $database->prepare('SELECT id FROM products WHERE name = ?');
            $stmt->execute([$product->getName()]);
            $productId = $stmt->fetchColumn();

            $stmt = $database->prepare('INSERT INTO meal_products (meal_id, product_id, quantity) VALUES (?, ?, ?)');
            $stmt->execute([$mealId, $productId, $quantity]);


        }

        // Get user ID from session for currently logged-in user
        $userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        if ($userID) {
            $stmt = $database->prepare('INSERT INTO user_meals (user_id, meal_id) VALUES (?, ?)');
            $stmt->execute([$userID, $mealId]);
        } else {
            return ['error' => 'User not logged in.'];
        }

        return ['success' => 'Meal added successfully.'];
    }


    public function updateMeal(Meal $meal, int $mealId): array
    {
        $database = $this->database->connect();

        // Check if meal exists
        $stmt = $database->prepare('SELECT COUNT(*) FROM meals WHERE id = ?');
        $stmt->execute([$mealId]);
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            return ['error' => 'Meal not found.'];
        }

        // Update meal name
        $stmt = $database->prepare('UPDATE meals SET name = ? WHERE id = ?');
        $stmt->execute([$meal->getName(), $mealId]);

        // Remove old meal products
        $stmt = $database->prepare('DELETE FROM meal_products WHERE meal_id = ?');
        $stmt->execute([$mealId]);

        foreach ($meal->getProducts() as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'];

            // Insert or ignore duplicate products
            $stmt = $database->prepare('
            INSERT INTO products (name, carbohydrates, fats, protein, fibre, kcal)
            VALUES (?, ?, ?, ?, ?, ?)
            ON CONFLICT (name) DO NOTHING
        ');
            $stmt->execute([
                $product->getName(),
                $product->getCarbohydrates(),
                $product->getFats(),
                $product->getProtein(),
                $product->getFibre(),
                $product->getKcal()
            ]);

            // Get product ID
            $stmt = $database->prepare('SELECT id FROM products WHERE name = ?');
            $stmt->execute([$product->getName()]);
            $productId = $stmt->fetchColumn();

            // Insert new meal product relation
            $stmt = $database->prepare('INSERT INTO meal_products (meal_id, product_id, quantity) VALUES (?, ?, ?)');
            $stmt->execute([$mealId, $productId, $quantity]);
        }

        return ['success' => 'Meal updated successfully.'];
    }



    public function getMeals(): array
    {
        $result = [];
        $userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        if (!$userID) {
            return ['error' => 'User not logged in.'];
        }

        $stmt = $this->database->connect()->prepare("
        SELECT m.id AS m_id, m.name AS m_name, m.created_at, 
               p.id AS p_id, p.name AS p_name, mp.quantity, 
               p.carbohydrates, p.fats, p.protein, p.fibre, p.kcal
        FROM meals m
        JOIN meal_products mp ON mp.meal_id = m.id
        JOIN products p ON p.id = mp.product_id
        JOIN user_meals um ON um.meal_id = m.id AND um.user_id = :user_id
    ");

        $stmt->bindParam(':user_id', $userID, \PDO::PARAM_INT);
        $stmt->execute();
        $meals = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $mealMap = []; // Store meals by ID

        foreach ($meals as $row) {
            $mealId = $row['m_id'];

            // If meal does not exist in the map, create it
            if (!isset($mealMap[$mealId])) {
                $meal = new Meal($row['m_name'], $row['created_at'], $mealId);
                $mealMap[$mealId] = $meal;
                $result[] = $meal;
            }

            // Get the meal instance
            $meal = $mealMap[$mealId];

            // Check if product is already added to the meal
            $existingProducts = $meal->getProducts();
            $productExists = false;

            foreach ($existingProducts as $existingItem) {
                if ($existingItem['product']->getId() === $row['p_id']) {
                    $productExists = true;
                    break;
                }
            }

            // If the product is not already added, add it
            if (!$productExists) {
                $product = new Product(
                    $row['p_name'],
                    $row['carbohydrates'],
                    $row['fats'],
                    $row['protein'],
                    $row['fibre'],
                    $row['kcal'],
                    $row['p_id']
                );

                $meal->addProduct($product, (float) $row['quantity']);
            }
        }

        return $result;
    }



}

