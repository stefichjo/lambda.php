<?php

class Maybe implements Fluent {
    public function bind(Closure $f) {
        switch (get_class($this)) {
            case 'Nothing':     return $this;
            case 'Just':        return $f($this->value);
        }
    }
}
class Nothing extends Maybe {}
class Just extends Maybe { function __construct($value) { $this->value = $value; } }

