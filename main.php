<?php
require('adt.php');
require('curry.php');
require('fp.php');
require('monad.php');
require('typeclass.php');

$add = autoCurry(
	function($a, $b) { return $a + $b; }
);
$inc = $add(1);
var_dump($inc(42));

$foo = autoCurry(
	function($a, $b, $c) { return ($a + $b) * $c; }
);
$foo1 = $foo(1);
$foo12 = $foo1(2);
var_dump($foo12(3));
