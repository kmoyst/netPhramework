<?php

namespace netPhramework\locating;

use netPhramework\locating\rerouters\Rerouter;

class ReroutedPath extends Path
{
	private bool $rerouted = false;

	public function __construct(
		private readonly MutablePath $path,
		private readonly Rerouter $rerouter) {}

	private function reroute():self
	{
		if(!$this->rerouted)
		{
			$this->rerouter->reroute($this->path);
			$this->rerouted = true;
		}
		return $this;
	}

	public function getName(): ?string
	{
		$this->reroute();
		return $this->path->getName();
	}

	public function getNext(): ?Path
	{
		$this->reroute();
		return $this->path->getNext();
	}

}