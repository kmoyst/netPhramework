<?php

namespace netPhramework\runtime;

interface RuntimeVariables
{
	public function get(string $key):?string;
}