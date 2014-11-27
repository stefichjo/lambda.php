<?php

interface ShapeTypeClass {
    public function area();
}

class RectangleType implements ShapeTypeClass {
    function __construct($width, $height) {
        $this->width = $width;
        $this->height = $height;
    }

    public function area() {
        return $this->width * $this->height;
    }
}

class CircleType implements ShapeTypeClass {
    function __construct($radius) {
        $this->radius = $radius;
    }

    public function area() {
        return $this->radius * pow(pi(), 2);
    }
}

//var_dump((new RectangleType(13, 23))->area()); // -> 299
//var_dump((new CircleType(42))->area()); // -> 414.523...
