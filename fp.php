<?php

// ((b -> c), (a -> b)) -> (a -> c)
function compose(Closure $f, Closure $g) {
    return function($arg) use ($f, $g) {
        return $f($g($arg));
    };
}

// ((a, b, c, ...) -> z) -> (a -> b -> c -> ... -> z)
function autoCurry(Closure $f) {
	$autoCurryPrivate = function (Closure $f, $args, $argsCount) use (&$autoCurryPrivate) {
		if ($argsCount > 1)	{
			return function($arg) use ($f, $args, $argsCount, &$autoCurryPrivate) {
				return $autoCurryPrivate($f, array_merge($args, [$arg]), $argsCount - 1);
			};
		} else {
			return function($arg) use ($f, $args) {
				return call_user_func_array($f, array_merge($args, [$arg]));
			};
		}
	};

	$argsCount = (new ReflectionMethod($f, '__invoke'))->getNumberOfParameters();

	if ($argsCount > 0) {
		return $autoCurryPrivate($f, [], $argsCount);
	} else {
		return $f;
	}
}
