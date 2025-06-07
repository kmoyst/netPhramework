<?php

namespace netPhramework\locating;

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
	 * @param MutablePath|string|null $next
	 * @return $this
	 * @throws PathException
	 */
	public function setNext(MutablePath|string|null $next): self
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

	public function getNext():?MutablePath
	{
		return $this->next;
	}

	public function append(MutablePath|string $tail):MutablePath
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

    private function parsePath(MutablePath|string|null $path):?MutablePath
    {
        if(is_string($path))
			return new MutablePath()->setName($path);
		else
			return $path;
    }

    public function __clone():void
    {
        if($this->next !== null) $this->next = clone $this->next;
    }
}