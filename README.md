LambdaPHP
=========

Functional Programming Techniques in Object-Oriented Design Patterns

Installation
------------

Include `main.php`.

Features
========

Function Composition
--------------------

```php
$inc = function($a) { return $a + 1; };
$sqr = function($a) { return $a * $a; };

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
