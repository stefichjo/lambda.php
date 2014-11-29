<?php

class Maybe implements IoC {
    public function inject(Closure $f) {
        switch (get_class($this)) {
            case 'Nothing':     return $this;
            case 'Just':        return $f($this->value);
        }
    }
}
class Nothing extends Maybe {}
class Just extends Maybe { function __construct($value) { $this->value = $value; } }

// a -> Maybe<a>
$f = function($a) {
    if ($a <= 0) {
        return new Nothing();
    } else {
        return new Just($a - 1);
    }
};

$m = (new Just(5))->inject($f)->inject($f)->inject($f)->inject($f);


