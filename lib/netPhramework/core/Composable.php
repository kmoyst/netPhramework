<?php

namespace netPhramework\core;

interface Composable
{
	public function add(Node $component):self;
}