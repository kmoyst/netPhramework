<?php

namespace netPhramework\routing;

use netPhramework\exceptions\PathException;
use netPhramework\routing\rerouters\Rerouter;

class ReroutedPath extends Path
{
	private bool $rerouted = false;

	public function __construct(
		private readonly Path $path,
		private readonly Rerouter $rerouter)
	{}

	/**
	 * @return string|null
	 * @throws PathException
	 */
	public function getName(): ?string
	{
		$this->reroute();
		return parent::getName();
	}

	/**
	 * @return Path|null
	 * @throws PathException
	 */
	public function getNext(): ?Path
	{
		$this->reroute();
		return parent::getNext();
	}

	public function pop(): Path
	{
		$this->reroute();
		return parent::pop();
	}

	public function appendPath(Path $tail): Path
	{
		$this->reroute();
		return parent::appendPath($tail);
	}

	/**
	 * @return void
	 * @throws PathException
	 */
	private function reroute():void
	{
		if($this->rerouted) return;
		$this->rerouter->reroute($this->path);
		$this->setName($this->path->getName());
		$this->setNext($this->path->getNext());
	}
}