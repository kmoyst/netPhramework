<?php

namespace netPhramework\routing;

abstract class PathTemplate extends Path
{
	private bool $isParsed = false;

	private function parseOnce():void
	{
		if($this->isParsed) return;
		$this->isParsed = true;
		$this->parse();
	}

	public function getName(): ?string
	{
		$this->parseOnce();
		return parent::getName();
	}

	public function getNext(): ?Path
	{
		$this->parseOnce();
		return parent::getNext();
	}

	public function pop(): Path
	{
		$this->parseOnce();
		return parent::pop();
	}

	public function appendPath(Path $tail): Path
	{
		$this->parseOnce();
		return parent::appendPath($tail);
	}

	public function prependName(string $name): Path
	{
		$this->parseOnce();
		return parent::prependName($name);
	}

	abstract protected function parse():void;
}