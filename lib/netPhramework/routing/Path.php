<?php

namespace netPhramework\routing;

use netPhramework\exceptions\PathException;

class Path extends Route implements Reroutable
{
	private ?string $name = null;
	private ?Path $next = null;
	public bool $debugOn = false;

	public function getName(): ?string
	{
		if($this->debugOn)
		{
			ob_start(); var_dump($this);
			echo "\n\nGET NAME RUN ON...\n\n".ob_get_clean()."\n\n";
		}
		return $this->name;
	}

	public function getNext(): ?Path
	{
		if($this->debugOn)
		{
			ob_start(); var_dump($this);
			echo "\n\nGET NEXT RUN ON...\n\n".ob_get_clean()."\n\n";
		}
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
		if($this->debugOn)
		{
			ob_start();
			var_dump($tail);
			echo "\n\nAPPENDING \n\n".ob_get_clean()."\n\n";
		}
		if($this->getName() === null)
		{
			$this->setName($tail->getName());
			$this->setNext($tail->getNext());
		}
		elseif($this->getNext() === null)
			$this->setNext($tail);
		else
			$this->getNext()->appendPath($tail);
		if($this->debugOn)
		{
			echo "\n\n***FINISHED APPENDING*** \n\n";
		}
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
		if($this->next !== null)
			$this->next = clone $this->next;
	}
}