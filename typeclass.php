<?php

interface DI {
	// $di->inject($make) == $di
	public function inject(Closure $f);
}

