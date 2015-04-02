<?php

class Container implements Fluent {
	function __construct($value) { $this->value = $value; }

	function bind(Closure $f) {
		return $f($this->value);
	}
}
