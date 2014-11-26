<?php

interface Shape {
    public function area();
}

class Rectangle implements Shape {
    function __construct($width, $height) {
        $this->width = $width;
        $this->height = $height;
    }

    public function area() {
        return $this->width * $this->height;
    }
}

class Circle implements Shape {
    function __construct($radius) {
        $this->radius = $radius;
    }

    public function area() {
        return $this->radius * pow(pi(), 2);
    }
}

var_dump((new Rectangle(13, 23))->area());
var_dump((new Circle(42))->area());
