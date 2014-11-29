<?php

class Listing implements IoC, Show {
	// ([a], (a -> [b])) -> [b]
    public function inject(Closure $f) {
        switch (get_class($this)) {
            case 'Nil':     return $this;
            case 'Cons':    return flatten(map($f, $this));
        }
    }

	// [a] -> array
    public function show() {
        switch (get_class($this)) {
            case 'Nil':     return array();
            case 'Cons':    return array_merge(array($this->head), $this->tail->show());
        }
    }

	// array -> [a]
    public static function read(array $array) {
        switch ($array) {
            case []:    return new Nil;
            default:    return new Cons(array_shift($array), Listing::read($array));
        }
    }
}
class Nil extends Listing {}
class Cons extends Listing { function __construct($head, Listing $tail) { $this->head = $head; $this->tail = $tail; } }

// ((a -> b), [a]) -> [b]
function map($f, Listing $listing) {
    switch (get_class($listing)) {
        case 'Nil':     return $listing;
        case 'Cons':    return new Cons($f($listing->head), map($f, $listing->tail));
    }
}

// [[a]] -> [a]
function flatten(Listing $listings) {
    switch (get_class($listings)) {
        case 'Nil':     return $listings;
        case 'Cons':    return concat($listings->head, flatten($listings->tail));
    }
}

// ([a], [a]) -> [a]
function concat(Listing $xs, Listing $ys) {
    switch (get_class($xs)) {
        case 'Nil':     return $ys;
        case 'Cons':    return new Cons($xs->head, concat($xs->tail, $ys));
    }
}

$listing123 = Listing::read([1, 2, 3]);
$listing456 = Listing::read([4, 5, 6]);
$listing126 = concat($listing123, $listing456);

// a -> [a]
$mirror = function($x) {
    return Listing::read([$x, -$x]);
};

$listing = $listing126->inject($mirror);


