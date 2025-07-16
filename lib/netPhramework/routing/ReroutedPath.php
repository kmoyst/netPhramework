<?php

namespace netPhramework\routing;

use netPhramework\exceptions\PathException;
use netPhramework\routing\rerouters\Rerouter;

class ReroutedPath extends Route
{
	private bool $rerouted = false;

	public function __construct
	(
	private readonly Path     $path,
	private readonly Rerouter $rerouter
	)
	{}

	/**
	 * @return self
	 * @throws PathException
	 */
	private function reroute():self
	{
		if(!$this->rerouted)
		{
			$this->rerouter->reroute($this->path);
			$this->rerouted = true;
		}
		return $this;
	}

	/**
	 * @return string|null
	 * @throws PathException
	 */
	public function getName(): ?string
	{
		$this->reroute();
		return $this->path->getName();
	}

	/**
	 * @return Route|null
	 * @throws PathException
	 */
	public function getNext(): ?Route
	{
		$this->reroute();
		return $this->path->getNext();
	}

}