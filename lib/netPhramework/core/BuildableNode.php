<?php

namespace netPhramework\core;

interface BuildableNode
{
	public function add(Node $node):self;
}