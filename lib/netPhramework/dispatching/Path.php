<?php

namespace netPhramework\dispatching;

/**
 * A fully readable and modifiable Path
 *
 */
class Path implements RelocatablePath, ReadablePath
{
	private ?string $name = null;
	private ?Path $next = null;

	public function setName(string $name): Path
	{
		$this->name = $name; // null can't be set outside class
		return $this;
	}

	public function getName():?string
	{
		return $this->name;
	}

	public function getNext():?Path
	{
		return $this->next;
	}

	public function append(Path|string $tail):Path
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

	public function pop():Path
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

	public function clear():Path
	{
		$this->name = null;
		$this->next = null;
		return $this;
	}

    private function parsePath(Path|string $path):Path
    {
        if(is_string($path))
			$path = new Path()->setName($path);
        return $path;
    }

    public function __clone():void
    {
        if($this->next !== null) $this->next = clone $this->next;
    }
}