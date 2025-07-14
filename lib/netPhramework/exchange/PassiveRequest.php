<?php

namespace netPhramework\exchange;

use netPhramework\nodes\Node;

class PassiveRequest extends Request
{
	public function andGetNode(): Node
	{
		return $this->route->toAPassiveNode();
	}
}