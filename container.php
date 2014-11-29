<?php

class Container implements IoC {
	function __construct($value) { $this->value = $value; }

	function inject(Closure $f) {
		return $f($this->value);
	}
}

$inc = function($a) { return $a + 1; }
$incContain = function ($a) use ($inc) {
	return new Container($inc($a));
};
