<?php

class Container implements DI {
	function __construct($value) { $this->value = $value; }

	function inject(Closure $f) {
		return $f($this->value);
	}
}
