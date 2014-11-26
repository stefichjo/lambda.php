<?php

interface Monad {
    public function inject(Closure $f);
}

interface Show {
    public function show();
}

class Maybe implements Monad {
    public function inject(Closure $f) {
        switch (get_class($this)) {
            case 'Nothing':     return $this;
            case 'Just':        return $f($this->m);
        }
    }
}
class Nothing extends Maybe {}
class Just extends Maybe { function __construct($m) { $this->m = $m; } }

$f = function($a) {
    if ($a <= 0) {
        return new Nothing();
    } else {
        return new Just($a - 1);
    }
};

$m = (new Just(5))->inject($f)->inject($f)->inject($f)->inject($f);
//var_dump($m);

class Listing implements Monad, Show {
    public function inject(Closure $f) {
        switch (get_class($this)) {
            case 'Nil':     return $this;
            case 'Cons':    return concat(map($f, $this));
        }
    }

    public function show() {
        switch (get_class($this)) {
            case 'Nil':     return '[]';
            case 'Cons':    return $this->head . ' ' . $this->tail->show();
        }
    }
}
class Nil extends Listing {}
class Cons extends Listing { function __construct($head, Listing $tail) { $this->head = $head; $this->tail = $tail; } }

function map($f, Listing $listing) {
    switch (get_class($listing)) {
        case 'Nil':     return $listing;
        case 'Cons':    return new Cons($f($listing->head), map($f, $listing->tail));
    }
}

function concat(Listing $listings) {
    switch (get_class($listings)) {
        case 'Nil':     return $listings;
        case 'Cons':    return conc($listings->head, concat($listings->tail));
    }
}

function conc(Listing $xs, Listing $ys) {
    switch (get_class($xs)) {
        case 'Nil':     return $ys;
        case 'Cons':    return new Cons($xs->head, conc($xs->tail, $ys));
    }
}


$listing123 = new Cons(1, new Cons(2, new Cons(3, new Nil)));
//echo $listing123->show();

$listing456 = new Cons(4, new Cons(5, new Cons(6, new Nil)));
//echo $listing456->show();

$listing126 = conc($listing123, $listing456);
//echo $listing126->show();

$mirror = function($x) {
    return new Cons($x, new Cons(-$x, new Nil));
};

//echo $listing126->inject($mirror)->show();

$inc = function($n) {
    return $n + 1;
};
$listing = new Cons(1, new Cons(2, new Cons(3, new Nil)));
//var_dump($listing);
//var_dump(map($inc, $listing));

