<?php

class Stack implements DI {
	// ([a], (a -> [b])) -> [b]
    public function inject(Closure $f) {
        switch (get_class($this)) {
            case 'Nil':     return $this;
            case 'Cons':    return flatten(map($f, $this));
        }
    }

	// [a] -> array
    public function toArray() {
        switch (get_class($this)) {
            case 'Nil':     return array();
            case 'Cons':    return array_merge(array($this->head), $this->tail->toArray());
        }
    }

	// array -> [a]
    public static function fromArray(array $array) {
        switch ($array) {
            case []:    return new Nil;
            default:    return new Cons(array_shift($array), Stack::fromArray($array));
        }
    }
}
class Nil extends Stack {}
class Cons extends Stack { function __construct($head, Stack $tail) { $this->head = $head; $this->tail = $tail; } }

// ([a], [a]) -> [a]
function concat(Stack $xs, Stack $ys) {
    switch (get_class($xs)) {
        case 'Nil':     return $ys;
        case 'Cons':    return new Cons($xs->head, concat($xs->tail, $ys));
    }
}

// ((a -> b), [a]) -> [b]
function map($f, Stack $stack) {
    switch (get_class($stack)) {
        case 'Nil':     return $stack;
        case 'Cons':    return new Cons($f($stack->head), map($f, $stack->tail));
    }
}

// [[a]] -> [a]
function flatten(Stack $stacks) {
    switch (get_class($stacks)) {
        case 'Nil':     return $stacks;
        case 'Cons':    return concat($stacks->head, flatten($stacks->tail));
    }
}

// Int -> [a] -> [a]
function take($int, Stack $xs) {
	switch (var_export($int <= 0, true) . get_class($xs)) {
		case 'true' . 'Nil':
		case 'true' . 'Cons':
		case 'false' . 'Nil': return new Nil;
		case 'false' . 'Cons': return new Cons($xs->head, take($int - 1, $xs->tail));
	}
}

function guard($bool) {
	return ($bool ? new Cons([], new Nil) : new Nil);
}

function filter($predicate, Stack $stack) {
	switch (get_class($stack)) {
		case 'Nil': return new Nil;
		case 'Cons': if($predicate($stack->head)) {
			return new Cons($stack->head, filter($predicate, $stack->tail));
		} else {
			return filter($predicate, $stack->tail);
		}
	}
}

$stack123 = Stack::fromArray([1, 2, 3]);
$stack456 = Stack::fromArray([4, 5, 6]);
$stack126 = concat($stack123, $stack456);

// a -> [a]
$mirror = function($x) {
    return Stack::fromArray([$x, -$x]);
};

$stack = $stack126->inject($mirror);


