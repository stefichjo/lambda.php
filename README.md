lambda.php
==========

`lambda.php` is an introduction to functional programming from an object-oriented prospective. The PHP code you will see actually works, but is not intended to be a reliable PHP extension (yet?).

Under Linux, run `php lambda.php` with your code in it.

If you want to take a look at Haskell from an object-oriented point of view, check out my repo [`spoon.hs`](https://github.com/stefichjo/spoon.hs).


Non-Features
============

These features are already available in PHP.

Lambda Expressions
------------------

Lambda Expressions, *Closures* or *Anonymous Functions* are available since PHP 5.3.

```php
function inc($a) { return $a + 1; }

$inc = function($a) { return $a + 1; };
echo $inc(42); // => 43

$sqr = function($a) { return $a * $a; };
echo $sqr(3); // => 9
```

Thanks to Lambda Expressions, functions are first-class citizens; they can appear as argument or result of other functions.

```php
function makeSieve($denominator) {
	return function($numerator) use ($denominator) {
		return ($numerator % $denominator != 0);
	};
}

$isOdd = makeSieve(2);
var_dump($isOdd(42)); // => false;
```

Types and Type Classes
----------------------

FP types are OOP classes, whereas FP type classes are OOP interfaces. (Given the term "type hinting", it shouldn't be much of a paradigm shift to think of classes as *types*.)


Algebraic Data Types, Pattern Matching, Inheritance
---------------------------------------------------

What a mouthful. Try "nested types" or "patterns" instead. The next example is an implementation of `Bool` as an ADT. It involves looking up the concrete type of `MyBool`, i.e. either `MyFalse` or `MyTrue`, which is a simple example of *pattern matching*, what essentially algebraic data types are all about.

```php
abstract class MyBool {
	function myNot() {
		switch (get_class($this)) {
			case 'MyFalse': 	return new MyTrue; break;
			case 'MyTrue': 		return new MyFalse; break;
		}
    }
}
class MyFalse extends MyBool {}
class MyTrue extends MyBool {}
```

We still have to know the way the arguments passed to the constructor are being stored. In Scala this as been standardized as *Case Classes*.


```php
abstract class Shape {
    public function area() {
        switch (get_class($this)) {
            case 'Rectangle':   return $this->width * $this->height;
            case 'Circle':      return $this->radius * pow(pi(), 2);
        }
    }
}
class Rectangle extends Shape { function __construct($width, $height) { $this->width = $width; $this->height = $height; } }
class Circle extends Shape { function __construct($radius) { $this->radius = $radius; } }
```

Referential Transparency, Immutability, Recursion
-------------------------------------------------

Again, what a mouthful. Try "cacheability" (or "memoizability") instead. A function is referential transparent when its return value doesn't depend on side effects (i.e. global state) but is determined only by the function's arguments. A function that "transparently references" its return value is also said to be "pure". Because pure functions can't reassign values to variables, for loops are forbidden. Instead, recursion is used excessively.

```php
function len($str) {
	if (substr($str, 0, 1) === false) {
		return 0;
	} else {
		return 1 + len(substr($str, 1));
	}
}

echo len('Hi!'); // => 3 = 1 + 1 + 1 + 0
```

Features
========

Function Composition
--------------------

```php
$sqrInc = compose($inc, $sqr);
echo $sqrInc(3); // => 10
```


Currying
--------

```php
$add = curry(
	function($a, $b) { return $a + $b; }
);
$inc = $add(1);
echo $inc(42); // => 43
```

Monad
-----

Whereas normally one passes a value to a function...

```php
echo $inc(42); // => 43
```

...one may also pass a function to a value. In order to do so, a value Identity object needs to implement an injector function that handles the function to be passed to the Identity object's value. This pattern is called *Inversion of Control*.

```php
echo (new Identity(42))->inject($inc); // => 43
```

In order to make Inversion of Control *composable*, each injection must also be an instantiation of a new value Identity. This pattern is called *Dependency Injection* or *Monad*.

```php
$inc = function($a) { return $a + 1; }
$incId = function($a) use ($inc) { return new Identity($inc($a)); };

var_dump((new Identity(42))->inject($incId)->inject($incId)); // => new Identity(44)
```


Maybe
-----

`Maybe` is an algebraic data type that comes in two flavors: `Nothing` and `Just`, with `Nothing` as a type-safer version of `null`, whereas `Just` corresponds to the above-mentioned `Identity` class.

```php
class Maybe {}
class Nothing extends Maybe {}
class Just extends Maybe { function __construct($value) { $this->value = $value; } }

$dec = function($a) { return $a - 1; };
$decMaybe = function($a) use ($dec) { return ($a <= 0 ? new Nothing : new Just($dec($a))); };

var_dump($decMaybe(42)); // => new Just(41)
var_dump($decMaybe(0)); // => new Nothing
```

Implementing the `IoC` interface, i.e. the `inject` function, the `Maybe` monad becomes composeable.

```php
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

var_dump((new Just(1))->inject($decMaybe)); // => new Just(0)
var_dump((new Just(1))->inject($decMaybe)->inject($decMaybe)); // => new Nothing
var_dump((new Just(1))->inject($decMaybe)->inject($decMaybe)->inject($decMaybe)); // => new Nothing
```

Since the `decMaybe` function and the `Maybe` class take care of all aspects of how to handle `Nothing` objects, there is no need to distinguish `Nothing` from `Just` when applying `decMaybe` to `Just(1)`. For comparison, here is an ordinary non-functional implementation of the above example that has to handle `null`.

```php
$d = null;
$a = 1;
$b = decMaybe($a);
if ($b !== null) {
	$c = decMaybe($b);
	if ($c !== null) {
		$d = decMaybe($c);
	}
}
var_dump($d);
```

Stack
-----

The `Stack` monad, as a matter of fact, is quite similar to the `Maybe` monad, but its "`Just`" part is recursive, which causes the elements of the stack to be linked.

```php
class Stack implements IoC { /* ... */ }
class Nil extends Stack {}
class Cons extends Stack { function __construct($head, Stack $tail) { $this->head = $head; $this->tail = $tail; } }
```

Stacks can be iterated (`map`ped) and filtered.

```php
$isEven = function($a) { return $a % 2 == 0; };
$inc = function($a) { return $a + 1; };
var_dump(map($inc, Stack::fromArray([1, 2, 3, 4]))->toArray()); // => [2, 3, 4, 5]
var_dump(filter($isEven, Stack::fromArray([1, 2, 3, 4]))->toArray()); // => [2, 4]
```

Next Steps
==========

Syntactic Sugar
---------------

Monadic expressions deserve an own notation.

Composability
-------------

The next example shuffles up two Stacks, `[1, 2]` and `['a', 'b']`. Note that ommiting `use ($i)` breaks our PHP code, whereas PHP's `use ($i)` breaks our composability.

```php
$ab12 = Stack::fromArray([1, 2])->inject(
	function($i) { return Stack::fromArray(['a', 'b'])->inject(
		function($j) use ($i) { return new Cons([$i, $j], new Nil); }
	); }
);
var_dump($ab12->toArray()); // => [[1, 'a'], [1, 'b'], [2, 'a'], [2, 'b']]
```

Lazy Evaluation
---------------

The next example is a function that shows the first 10 prime numbers. Note that due to strict evaluation, the base `Stack` from `2` to `1200` will be evaluated! Therefore the `Nil` case has to be implemented, whereas with lazy evalution the list might have no upper bound and this case could be ommitted.

```php
$primes = function() {
	$primesPrivate = function(Stack $ints) use (&$primesPrivate) {
		switch (get_class($ints)) {
			case 'Nil':
				return new Nil;
			case 'Cons':
				return new Cons(
					$ints->head,
					$primesPrivate(
						filter(
							makeSieve($ints->head),
							$ints->tail
						)
					)
				);
		}
	};

	return $primesPrivate(Stack::fromArray(range(2, 1200)));
};
var_dump(take(10, $primes())->toArray()); // => [2, 3, 5, 7, 11, 13, 17, 19, 23, 29]
```
