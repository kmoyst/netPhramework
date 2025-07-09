<?php

namespace netPhramework\core;

interface BuildableResource
{
	public function add(Resource $node):self;
}