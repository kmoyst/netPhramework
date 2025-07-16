<?php

namespace netPhramework\test;

use netPhramework\exceptions\PathException;

class Path implements Route
{
	public ?Path $next = null {get{return $this->next;}set(?Path $next){
		if($this->name === null && $next !== null)
			throw new PathException("Can't set next Path to a Path w/o a name");
		$this->next = $next;
	}}

	public function __construct
	(
	public ?string $name = null {get{return $this->name;}set(?string $name){
		if($name === null && $this->next !== null)
			throw new PathException("Can only set HEAD to null");
		$this->name = $name;
	}}
	)
	{}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function getNext(): ?Route
	{
		return $this->next;
	}

	public function setName(?string $name):self
	{
		$this->name = $name;
		return $this;
	}

	public function setNext(?Path $next): self
	{
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
		if(!isset($tail->name))
			throw new PathException("Can't append a path with no name");
		elseif($this->name === null)
		{
			$this->name = $tail->name;
			$this->next = $tail->next;
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
	public function appendNameAsPath(string $name):self
	{
		return $this->appendPath(new self($name));
	}

	/**
	 * @param Route $route
	 * @return $this
	 * @throws PathException
	 */
	public function appendRouteAsPath(Route $route):self
	{
		$path = new self($route->name);
		$this->traverse($path, $route->next);
		return $this->appendPath($path);
	}

	private function traverse(Path $path, ?Route $route):void
	{
		if($route === null) return;
		$path->next = new self($route->name);
		$this->traverse($path->next, $route->next);
	}

	public function __clone():void
	{
		if($this->next !== null) $this->next = clone $this->next;
	}
}