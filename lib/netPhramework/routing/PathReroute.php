<?php

namespace netPhramework\routing;

use netPhramework\exceptions\PathException;
use netPhramework\routing\rerouters\Rerouter;

class PathReroute extends PathTemplate
{
	public function __construct(
		private readonly Path $path,
		private readonly Rerouter $rerouter)
	{}

	/**
	 * @return void
	 * @throws PathException
	 */
	protected function parse():void
	{
		$this->rerouter->reroute($this->path);
		$this->setName($this->path->getName());
		$this->setNext($this->path->getNext());
	}
}