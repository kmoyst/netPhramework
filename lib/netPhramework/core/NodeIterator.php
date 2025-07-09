<?php

namespace netPhramework\core;

use Iterator;
use netPhramework\common\IsKeyedIterable;

abstract class NodeIterator implements Iterator
{
	use IsKeyedIterable;

	abstract public function current():Node;
}