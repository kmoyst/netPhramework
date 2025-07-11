<?php

namespace netPhramework\routing;

use netPhramework\exceptions\PathException;

class MutablePath extends Path implements Reroutable
{
	private ?string $name;
	private ?MutablePath $next;

	public function __construct(?string $name = null)
	{
		$this->name = $name;
		$this->next = null;
	}

	public function setName(string $name): self
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @param Path|string|null $next
	 * @return $this
	 * @throws PathException
	 */
	public function setNext(Path|string|null $next): self
	{
		if($this->name === null && $next !== null)
			throw new PathException("Tried to set next on a Path w/o name");
		$this->next = $this->parsePath($next);
		return $this;
	}

	public function getName():?string
	{
		return $this->name;
	}

	public function getNext():?self
	{
		return $this->next;
	}

	/**
	 * @param Path|string $tail
	 * @return $this
	 * @throws PathException
	 */
	public function append(Path|string $tail):self
	{
		if($this->name === null)
		{
			$parsed = $this->parsePath($tail);
			$this->name = $parsed->getName();
			$this->next = $parsed->getNext();
		}
		elseif($this->next === null)
			$this->next = $this->parsePath($tail);
		else
			$this->next->append($tail);
		return $this;
	}

	/**
	 * "Safer" method to append a name to the linked list
	 *
	 * @param string $name
	 * @return $this
	 */
	public function appendName(string $name):self
	{
		if($this->name === null)
			$this->name = $name;
		elseif($this->next === null)
			$this->next = new self($name);
		else
			$this->next->appendName($name);
		return $this;
	}

	public function pop():self
	{
		if($this->name === null)
			return $this;
		elseif($this->next === null)
			$this->name = null;
		elseif($this->next->getNext() === null)
			$this->next = null;
		else
			$this->next->pop();
		return $this;
	}

	public function clear():self
	{
		$this->name = null;
		$this->next = null;
		return $this;
	}

	/**
	 * @param Path|string|null $path
	 * @return self|null
	 * @throws PathException
	 */
    private function parsePath(Path|string|null $path):?self
    {
        if(is_string($path))
			return new self()->setName($path);
		elseif($path instanceof self || $path === null)
			return $path;
		else
		{
			$mutablePath = new self($path->getName());
			$this->traverse($mutablePath, $path->getNext());
			return $mutablePath;
		}
    }

	/**
	 * @param self $mutablePath
	 * @param Path|null $path
	 * @return void
	 * @throws PathException
	 */
	private function traverse(self $mutablePath, ?Path $path):void
	{
		if($path === null) return;
		$mutablePath->setNext(new self($path->getName()));
		$this->traverse($mutablePath->getNext(), $path->getNext());
	}

    public function __clone():void
    {
        if($this->next !== null) $this->next = clone $this->next;
    }
}