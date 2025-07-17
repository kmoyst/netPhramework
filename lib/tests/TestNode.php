<?php

namespace tests;

use netPhramework\nodes\Node;
use netPhramework\routing\Path;

interface TestNode
{
	public function getRoot():Node;

	public function basePath():Path;

	public function fromCli():Path;

	public function fromArray():Path;

	public function fromUri():Path;

	public function fromArrayTail():Path;

	public function fromArrayHead():Path;

	public function fromUriHead():Path;

	public function fromUriTail():Path;
}