<?php

namespace netPhramework\test;

interface Route
{
	public ?string $name {get;}
	public ?Route $next {get;}

	public function getName():?string;
	public function getNext():?Route;
}