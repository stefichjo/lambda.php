<?php
require('adt.php');
//require('curry.php');
require('fp.php');
require('monad.php');
require('typeclass.php');

function autoCurry3(Closure $f) {
	return function($a) use ($f) {
		return function($b) use ($f, $a) {
			return function($c) use ($f, $a, $b) {
				return $f($a, $b, $c);
			};
		};
	};
}

$add3 = function($a, $b, $c) { return $a + $b + $c; };
echo $add3(1, 2, 4);


$add3curried = autoCurry3($add3);

$addFirst = $add3curried(1);
$addSecond = $addFirst(2);
$addThird = $addSecond(4);

echo $addThird;
