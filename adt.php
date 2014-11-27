<?php

// area :: ShapeAdt -> Float
// area (Rectangle width height) = width * height
// area (Circle radius) = radius
class ShapeAdt {
    public function area() {
        switch (get_class($this)) {
            case 'Rectangle':   return $this->width * $this->height;
            case 'Circle':      return $this->radius * pow(pi(), 2);
        }
    }
}

// data ShapeAdt = Rectangle { width :: Float, height :: Float } | ...
class Rectangle extends ShapeAdt {
    function __construct($width, $height) {
        $this->width = $width;
        $this->height = $height;
    }
}

// data ShapeAdt = ... | Circle { radius :: Float }
class Circle extends ShapeAdt {
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
