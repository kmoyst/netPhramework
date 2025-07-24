<?php

namespace netPhramework\core;

interface RuntimeVariables
{
	public function get(string $key):?string;
}