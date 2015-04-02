<?php

interface Fluent {
	// $object->inject($make) == $object
	public function bind(Closure $f);
}

