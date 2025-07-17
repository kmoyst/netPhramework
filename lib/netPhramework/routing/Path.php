<?php

namespace netPhramework\routing;

use netPhramework\exceptions\PathException;

class Path extends Route implements Reroutable
{
	private ?string $name = null;
	private ?Path $next = null;

	public function getName(): ?string
	{
		return $this->name;
	}

	public function getNext(): ?Path
	{
		return $this->next;
	}

	/**
	 * @param string|null $name
	 * @return $this
	 */
	public function setName(?string $name):Path
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @param Path|null $next
	 * @return $this
	 */
	public function setNext(?Path $next): Path
	{
		$this->next = $next;
		return $this;
	}

	/**
	 *
	 * @return $this
	 * @throws PathException
	 */
	public function pop():Path
	{
		if($this->name === null)
			return $this;
		elseif($this->next === null)
			$this->name = null;
		elseif($this->next->next === null)
			$this->next = null;
		else
			$this->next->pop();
		return $this;
	}

	public function clear():Path
	{
		$this->next = null;
		$this->name = null;
		return $this;
	}

	/**
	 * @param Path $tail
	 * @return $this
	 * @throws PathException
	 */
	public function appendPath(Path $tail):Path
	{
		if($this->getName() === null)
		{
			$this->name = $tail->getName();
			$this->next = $tail->getNext();
		}
		elseif($this->getNext() === null)
			$this->next = $tail;
		else
			$this->next->appendPath($tail);
		return $this;
	}

	/**
	 * @param string $name
	 * @return $this
	 * @throws PathException
	 */
	public function appendName(string $name):Path
	{
		return $this->appendPath(new self()->setName($name));
	}

	/**
	 * @param Route $route
	 * @return $this
	 * @throws PathException
	 */
	public function appendRoute(Route $route):Path
	{
		return $this->appendPath(new PathFromRoute($route));
	}

	public function __clone():void
	{
		if($this->next !== null) $this->next = clone $this->next;
	}
}