<?php
function autoCurry(Closure $f) {
	$argsCount = (new ReflectionMethod($f, '__invoke'))->getNumberOfParameters();

	if ($argsCount > 0) {
		return autoCurryMain($f, [], $argsCount);
	} else {
		return $f;
	}
}

function autoCurryMain(Closure $f, $args, $argsCount) {
	if ($argsCount > 1)	{
		return function($arg) use ($f, $args, $argsCount) {
			return autoCurryMain($f, array_merge($args, [$arg]), $argsCount - 1);
		};
	} else {
		return function($arg) use ($f, $args) {
			return call_user_func_array($f, array_merge($args, [$arg]));
		};
	}
}

