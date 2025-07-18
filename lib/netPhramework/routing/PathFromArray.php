<?php

namespace netPhramework\routing;

class PathFromArray extends Path
{
	private bool $isParsed = false;

	public function __construct(private readonly array $names) {}

	public function getName(): ?string
	{
		$this->parse();
		return parent::getName();
	}

	public function getNext(): ?Path
	{
		$this->parse();
		return parent::getNext();
	}

	public function pop(): Path
	{
		$this->parse();
		return parent::pop();
	}

	public function appendPath(Path $tail): Path
	{
		$this->parse();
		return parent::appendPath($tail);
	}

	private function parse():void
	{
		if($this->isParsed) return;
		$this->isParsed = true;
		$this->setName($this->names[0]);
		if(count($this->names) > 1)
			$this->setNext(new PathFromArray(array_slice($this->names, 1)));
	}
}