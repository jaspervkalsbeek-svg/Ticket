<?php
class car {

    public string $brand;
    public string $color;

    function __construct($brand, $color) {
        $this->brand = $brand;
        $this->color = $color;
    }

    public function drive() {
        echo "The " . $this->color . " " . $this->brand . " is driving.";
    }

    public function brake() {
        echo "The " . $this->color . " " . $this->brand . " is braking.";
    }
}
    $car = new car(brand:"BMW", color:"red");
    $car->drive();

    $car = new car(brand:"Audi", color:"blue");
    $car    ->brake();