<?php

// ((b -> c), (a -> b)) -> (a -> c)
function compose(Closure $f, Closure $g) {
    return function($arg) use ($f, $g) {
        return $f($g($arg));
    };
}

// ((a, b, c, ...) -> z) -> (a -> b -> c -> ... -> z)
function curry(Closure $f) {
	$curryPrivate = function (Closure $f, $args, $argsCount) use (&$curryPrivate) {
		if ($argsCount > 1)	{
			return function($arg) use ($f, $args, $argsCount, &$curryPrivate) {
				return $curryPrivate($f, array_merge($args, [$arg]), $argsCount - 1);
			};
		} else {
			return function($arg) use ($f, $args) {
				return call_user_func_array($f, array_merge($args, [$arg]));
			};
		}
	};

	$argsCount = (new ReflectionMethod($f, '__invoke'))->getNumberOfParameters();

	if ($argsCount > 0) {
		return $curryPrivate($f, [], $argsCount);
	} else {
		return $f;
	}
}

function makeModFilter($n) {
	return function($x) use ($n) {
		return ($x % $n != 0);
	};
}

