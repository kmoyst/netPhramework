<?php

namespace netPhramework\routing;

use netPhramework\exceptions\PathException;

class Path extends Route implements Reroutable
{
	protected ?string $name = null;
	protected ?Path $next = null;

	public function getName(): ?string
	{
		return $this->name;
	}

	public function getNext(): ?self
	{
		return $this->next;
	}

	/**
	 * @param string|null $name
	 * @return $this
	 * @throws PathException
	 */
	public function setName(?string $name):self
	{
		//if($name === null && $this->next !== null)
		//	throw new PathException("Can only set HEAD to null");
		$this->name = $name;
		return $this;
	}

	/**
	 * @param Path|null $next
	 * @return $this
	 * @throws PathException
	 */
	public function setNext(?Path $next): self
	{
		//if($this->name === null && $next !== null)
		//	throw new PathException("Can't set next Path to a Path w/o a name");
		$this->next = $next;
		return $this;
	}

	/**
	 *
	 * @return $this
	 * @throws PathException
	 */
	public function pop():self
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

	public function clear():self
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
	public function appendPath(self $tail):self
	{
//		if(!isset($tail->name))
//			throw new PathException("Can't append a path with no name");
//		elseif($this->name === null)
		if($this->name === null)
		{
			$this->name = $tail->getName();
			$this->next = $tail->getNext();
		}
		elseif($this->next === null)
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
	public function appendName(string $name):self
	{
		return $this->appendPath(new self()->setName($name));
	}

	/**
	 * @param Route $route
	 * @return $this
	 * @throws PathException
	 */
	public function appendRoute(Route $route):self
	{
		$path = new self()->setName($route->getName());
		$this->traverse($path, $route->getNext());
		return $this->appendPath($path);
	}

	/**
	 * @param Path $path
	 * @param Route|null $route
	 * @return void
	 * @throws PathException
	 */
	private function traverse(Path $path, ?Route $route):void
	{
		if($route === null) return;
		$path->setNext(new self()->setName($route->getName()));
		$this->traverse($path->next, $route->getNext());
	}

	public function __clone():void
	{
		if($this->next !== null) $this->next = clone $this->next;
	}
}