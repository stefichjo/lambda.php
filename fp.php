<?php

// ((b -> c), (a -> b)) -> (a -> c)
function compose(Closure $f, Closure $g) {
    return function($arg) use ($f, $g) {
        return $f($g($arg));
    };
}

// a -> a
$inc = function ($a) { return $a + 1; };

// a -> a
$square = function ($a) { return $a * $a; };

// a -> a
$squareInc = compose($square, $inc);

//echo $squareInc(2) . "\n"; // -> 9
