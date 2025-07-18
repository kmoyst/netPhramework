<?php

namespace netPhramework\resources;

use netPhramework\common\Utils;
use netPhramework\exchange\Exchange;
use netPhramework\nodes\ResourceSet;

class Index extends Page
{
	private ResourceSet $nodeSet;

	public function setNodeSet(ResourceSet $nodeSet): self
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
			$links[$node->getResourceId()] = $desc;
		}
		ksort($links);
		$this->add('links', $links);
		parent::handleExchange($exchange);
	}
}