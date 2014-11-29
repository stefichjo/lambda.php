<?php

function argsCount(Closure $f) {
    return (new ReflectionMethod($f, '__invoke'))->getNumberOfParameters();
}

function autoCurry(Closure $f) {
	return autoCurryOnce($f, [], argsCount($f));
}

function autoCurryOnce(Closure $f, $args, $argsCount) {
	if ($argsCount > 1)
	{
		return function($arg) use ($f, $args, $argsCount) {
			return autoCurryOnce($f, array_merge([$arg], $args), $argsCount - 1);
		};
	}
	else
	{
		return function($arg) use ($f, $args) {
			return call_user_func_array($f, array_reverse(array_merge([$arg], $args)));
		};
	}
}

