<?php

// area :: Shape -> Float
// area (Rectangle width height) = width * height
// area (Circle radius) = radius
class Shape {
    public function area() {
        switch (get_class($this)) {
            case 'Rectangle':   return $this->width * $this->height;
            case 'Circle':      return $this->radius * pow(pi(), 2);
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
// area rect
var_dump($rect->area());

// circ = Circle 42
$circ = new Circle(42);
// area circ
var_dump($circ->area());
