<?php

interface Monad {
	// (this<a>, (a -> this<b>)) -> this<b>
	public function flatMap(Closure $f);
}
interface Show {
	// this -> String
	public function show();
}
interface Read {
	// String -> this
	public function read();
}

class Maybe implements Monad {
    public function flatMap(Closure $f) {
        switch (get_class($this)) {
            case 'Nothing':     return $this;
            case 'Just':        return $f($this->m);
        }
    }
}
class Nothing extends Maybe {}
class Just extends Maybe { function __construct($m) { $this->m = $m; } }

// a -> Maybe<a>
$f = function($a) {
    if ($a <= 0) {
        return new Nothing();
    } else {
        return new Just($a - 1);
    }
};

$m = (new Just(5))->flatMap($f)->flatMap($f)->flatMap($f)->flatMap($f);

class Listing implements Monad, Show {
	// ([a], (a -> [b])) -> [b]
    public function flatMap(Closure $f) {
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

$listing = $listing126->flatMap($mirror);


