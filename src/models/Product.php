<?php

namespace models;

class Product
{
    private $id;
    private $name;
    private $carbohydrates;
    private $fats;
    private $protein;
    private $fibre;
    private $kcal;

    public function __construct($name, $carbohydrates = 0, $fats = 0, $protein = 0, $fibre = 0, $kcal = 0, $id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->carbohydrates = max(0, $carbohydrates); // Ensure non-negative values
        $this->fats = max(0, $fats);
        $this->protein = max(0, $protein);
        $this->fibre = max(0, $fibre);
        $this->kcal = max(0, $kcal);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCarbohydrates()
    {
        return $this->carbohydrates;
    }

    public function getFats()
    {
        return $this->fats;
    }

    public function getProtein()
    {
        return $this->protein;
    }

    public function getFibre()
    {
        return $this->fibre;
    }

    public function getKcal()
    {
        return $this->kcal;
    }

    /**
     * Convert the product to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'carbohydrates' => $this->carbohydrates,
            'fats' => $this->fats,
            'protein' => $this->protein,
            'fibre' => $this->fibre,
            'kcal' => $this->kcal
        ];
    }
}