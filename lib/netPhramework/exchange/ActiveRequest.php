<?php

namespace netPhramework\exchange;

use netPhramework\nodes\Node;

class ActiveRequest extends Request
{
	public function andGetNode():Node
	{
		$this->location->getParameters()
			->clear()
			->merge($this->environment->postParameters);
		return $this->route->toAnActiveNode();
	}
}