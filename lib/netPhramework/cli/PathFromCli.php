<?php

namespace netPhramework\cli;

use netPhramework\routing\Path;

class PathFromCli extends Path
{
	private string $name;
	private bool $askForMore;

	public function getName(): string
	{
		if(!isset($this->name))
		{
			$answer = readline("Requesting Asset? [Y/n default n]: ");
			if($answer === 'Y')
			{
				$this->name = $this->getAssetName();
				$this->askForMore = true;
				echo "\nRequesting asset '$this->name'...\n\n";
			}
			else
			{
				$this->name = readline("Resource name? (blank for default): ");
				$this->askForMore = false;
				if($this->name !== '')
					echo "\nRequesting resource '$this->name'...\n\n";
				else
					echo "\nRequesting default resource...\n\n";
			}
		}
		return $this->name;
	}

	public function getNext(): ?Path
	{
		if(!$this->askForMore) return null;
		else return new self;
	}

	private function getAssetName():?string
	{
		$name = readline("Asset name? ");
		if($name !== '') return $name;
		else echo "Asset name can't be empty\r\n";
		return $this->getAssetName();
	}
}