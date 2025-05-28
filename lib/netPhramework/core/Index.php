<?php

namespace netPhramework\core;

use netPhramework\common\Utils;

class Index extends Page
{
	private NodeSet $nodes;

	public function setComponents(NodeSet $nodes): self
	{
		$this->nodes = $nodes;
		return $this;
	}

	public function handleExchange(Exchange $exchange): void
	{
		$links = [];
		foreach($this->nodes as $name => $node)
		{
			$desc = Utils::kebabToSpace($name);
			if($node->isComposite()) $name .= '/';
			$links[$name] = $desc;
		}
		ksort($links);
		$this->add('links', $links);
		parent::handleExchange($exchange);
	}
}