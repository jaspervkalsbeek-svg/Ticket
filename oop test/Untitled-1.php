<?php
class car {
    public string $brand;
    public string $color;

    function __construct($brand, $color)
    {
        $this->brand = $brand;
        $this->color = $color;
    }

    function drive()
    {
        echo "The {$this->color} {$this->brand} is driving.";
    }

    function brake()
    {
        echo "The {$this->color} {$this->brand} is braking.";
    }

    function accelerate()
    {
        echo "The {$this->color} {$this->brand} is accelerating.";
    }

}

$car = new car("Toyota", "red");
$car->drive();
$car->brake();
$car->accelerate();

$car = new car("Honda", "blue");
$car->drive();
$car->brake();
$car->accelerate();
?>