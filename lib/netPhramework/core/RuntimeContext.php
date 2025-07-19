<?php

namespace netPhramework\core;

interface RuntimeContext
{
	public function get(string $key):?string;
}