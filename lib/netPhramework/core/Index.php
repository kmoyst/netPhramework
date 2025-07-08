<?php

namespace netPhramework\core;

use netPhramework\common\Utils;

class Index extends Page
{
	private HasNodes $nodeSet;

	public function setNodeSet(HasNodes $nodeSet): self
	{
		$this->nodeSet = $nodeSet;
		return $this;
	}

	public function handleExchange(Exchange $exchange): void
	{
		$links = [];
		foreach($this->nodeSet as $node)
		{
			$desc = Utils::kebabToSpace($node->getName());
			$links[$node->getNodeId()] = $desc;
		}
		ksort($links);
		$this->add('links', $links);
		parent::handleExchange($exchange);
	}
}