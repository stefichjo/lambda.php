<?php

// area :: Shape -> Float
// area (Rectangle width height) = width * height
// area (Circle radius) = radius
abstract class Shape {
    public function area() {
        switch (get_class($this)) {
            case 'Rectangle':   return $this->width * $this->height;
            case 'Circle':      return pow($this->radius, 2) * pi());
        }
    }
}

// data Shape = Rectangle { width :: Float, height :: Float } | ...
class Rectangle extends Shape {
    function __construct($width, $height) {
        $this->width = $width;
        $this->height = $height;
    }
}

// data Shape = ... | Circle { radius :: Float }
class Circle extends Shape {
    function __construct($radius) {
        $this->radius = $radius;
    }
}

// rect = Rectangle 13 23
$rect = new Rectangle(13, 23);

// circ = Circle 42
$circ = new Circle(42);

//var_dump($rect->area()); // -> 299
//var_dump($circ->area()); // -> 414.523...
