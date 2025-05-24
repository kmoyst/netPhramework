<?php

namespace netPhramework\core;

use netPhramework\common\Utils;

class Index extends Page
{
	private ComponentSet $components;

	public function setComponents(ComponentSet $components): self
	{
		$this->components = $components;
		return $this;
	}

	public function handleExchange(Exchange $exchange): void
	{
		$links = [];
		foreach($this->components as $name => $component)
		{
			$desc = Utils::kebabToSpace($name);
			if($component instanceof Composite)
				$name .= '/';
			$links[$name] = $desc;
		}
		ksort($links);
		$this->add('links', $links);
		parent::handleExchange($exchange);
	}
}