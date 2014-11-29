LambdaPHP
=========

Functional Programming Techniques implemented with Object-Oriented Design Patterns

Installation
============

Include `main.php`.

Function Composition
====================

```php
$inc = function($a) { return $a + 1; };
$sqr = function($a) { return $a * $a; };

$sqrAndInc = compose($inc, $sqr);
echo $sqrAndInc(7); // -> 50
```
