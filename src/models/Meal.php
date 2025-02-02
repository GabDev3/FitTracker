<?php

namespace models;

class Meal
{
    private $id;
    private $name;
    private $createdAt;
    private $products = []; // Array to store products and their quantities

    public function __construct($name, $createdAt = null, $id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->createdAt = $createdAt ?? date('Y-m-d H:i:s');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function addProduct(Product $product, float $quantity)
    {
        foreach ($this->products as &$item) {
            if ($item['product']->getName() === $product->getName()) {
                // If product already exists, increase the quantity
                $item['quantity'] += $quantity;
                return;
            }
        }

        // If product is not found, add it as new
        $this->products[] = [
            'product' => $product,
            'quantity' => $quantity
        ];
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function calculateNutrients()
    {
        $totalCarbohydrates = 0;
        $totalFats = 0;
        $totalProtein = 0;
        $totalFibre = 0;
        $totalKcal = 0;

        foreach ($this->products as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'];

            $totalCarbohydrates += $product->getCarbohydrates() * $quantity / 100;
            $totalFats += $product->getFats() * $quantity / 100;
            $totalProtein += $product->getProtein() * $quantity / 100;
            $totalFibre += $product->getFibre() * $quantity / 100;
            $totalKcal += $product->getKcal() * $quantity / 100;
        }

        return [
            'carbohydrates' => $totalCarbohydrates,
            'fats' => $totalFats,
            'protein' => $totalProtein,
            'fibre' => $totalFibre,
            'kcal' => $totalKcal
        ];
    }
}