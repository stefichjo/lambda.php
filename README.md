LambdaPHP
=========

Functional Programming Techniques in Object-Oriented Design Patterns

Installation
------------

Include `main.php`.

Non-Features
============

These features are already features of PHP.

Lambda Expressions
------------------

Lambda Expressions, *Closures* or *Anonymous Functions* are available since PHP 5.3.

```php
$inc = function($a) { return $a + 1; };
echo $inc(42); // -> 43

$sqr = function($a) { return $a * $a; };
echo $sqr(3); // -> 9
```

Features
========

Function Composition
--------------------

```php
$sqrAndInc = compose($inc, $sqr);
echo $sqrAndInc(3); // -> 10
```

Currying
--------

```php
$add = autoCurry(
	function($a, $b) { return $a + $b; }
);
$inc = $add(1);
echo $inc(42); // -> 43
```

Algebraic Data Type
-------------------

```php
// ...
```

Constructor Pattern Matching
----------------------------

```php
// ...
```

Type Class
----------

```php
// ...
```

Monad
-----

Whereas normally one passes a value to a function...

```php
echo $inc(42); // -> 43
```

...one may also pass a function to a value. In order to do so, a value container object needs to implement an injector function that handles the function to be passed to the container object's value.

```php
echo (new Container(42))->inject($inc); // -> 43
```

This pattern is called *Inversion of Control* (*IoC*).

```php
class Container implements IoC {
	function __construct($value) { $this->value = $value; }

	function inject(Closure $f) {
		return $f($this->value);
	}
}
```

In order to make Inversion of Control composable, the injector function needs to accept value container constructors. This pattern is called *Monad*.

```php
$containerInc = function($a) use ($inc) { return new Container($inc($a)); };

var_dump((new Container(42))->inject($containerInc)->inject($containerInc)); // -> Container(44)
```


