<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        abstract class Product
        {
            protected $price;
            protected $revenue; 
        
            public function __construct($price)
            {
                $this->price = $price;
            }
        
            abstract public function getFinalPrice();
        }
        
        class DigitalProduct extends Product
        {
            public function getFinalPrice()
            {
                $this->revenue = $this->price / 2; 
                return $this->revenue;
            }
        }
        
        class IndividualProduct extends Product
        {
            protected $quantity;
        
            public function __construct($price, $quantity)
            {
                parent::__construct($price);
                $this->quantity = $quantity;
            }
        
            public function getFinalPrice()
            {
                $this->revenue = $this->price * $this->quantity; // Вычисляем доход
                return $this->revenue;
            }
        }
        
        class WeightedProduct extends Product
        {
            protected $weight;
        
            public function __construct($price, $weight)
            {
                parent::__construct($price);
                $this->weight = $weight;
            }
        
            public function getFinalPrice()
            {
                $this->revenue = $this->price * $this->weight; // Вычисляем доход
                return $this->revenue;
            }
        }

        $digitalProduct = new DigitalProduct(20);
        echo "Digital Product: Final Price: " . $digitalProduct->getFinalPrice() . ", Revenue: " . $digitalProduct->revenue;
        
        $individualProduct = new IndividualProduct(20, 3);
        echo "\nIndividual Product: Final Price: " . $individualProduct->getFinalPrice() . ", Revenue: " . $individualProduct->revenue;

        $weightedProduct = new WeightedProduct(20, 4.5);
        echo "\nWeighted Product: Final Price: " . $weightedProduct->getFinalPrice() . ", Revenue: " . $weightedProduct->revenue;
    ?>
</body>
</html>