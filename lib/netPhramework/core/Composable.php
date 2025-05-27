<?php

namespace netPhramework\core;

interface Composable
{
	public function add(Node $node):self;
}