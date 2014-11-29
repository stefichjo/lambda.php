<?php

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


