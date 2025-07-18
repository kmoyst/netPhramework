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
		if($this->getName() === null)
			return $this;
		elseif($this->getNext() === null)
			$this->setName(null);
		elseif($this->getNext()->getNext() === null)
			$this->setNext(null);
		else
			$this->next->pop();
		return $this;
	}

	public function clear():Path
	{
		$this->setName(null);
		$this->setNext(null);
		return $this;
	}

	/**
	 * @param Path $tail
	 * @return $this
	 * @throws PathException
	 */
	public function appendPath(Path $tail):Path
	{
		if($this->name === null)
		{
			$this->setName($tail->getName());
			$this->setNext($tail->getNext());
		}
		elseif($this->getNext() === null)
			$this->setNext($tail);
		else
			$this->getNext()->appendPath($tail);
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
		if(isset($this->next))
			$this->next = clone $this->next;
	}
}