<?php

namespace netPhramework\cli;

use netPhramework\routing\Path;

class PathFromCli extends Path
{
	private string $name;
	private bool $hasMore;

	public function getName(): string
	{
		if(!isset($this->name))
		{
			if(($answer = $this->getAssetName()) !== null)
			{
				$this->name = $answer;
				$this->hasMore = true;
				echo "\nRequesting asset '$this->name'...\n\n";
			} else {
				$this->name = readline("Resource name? (blank for default): ");
				$this->hasMore = false;
				if($this->name !== '')
					echo "\nRequesting resource '$this->name'...\n\n";
				else
					echo "\nRequesting default resource...\n\n";
			}
		}
		return $this->name;
	}

	private function getAssetName():?string
	{
		$name = readline("Enter Asset Name or . to specify resource: ");
		if($name === '')
		{
			echo "Asset name can't be empty\r\n";
			return $this->getAssetName();
		}
		elseif($name === '.') return null;
		else return $name;
	}

	public function getNext(): ?Path
	{
		if(!$this->hasMore) return null;
		else return new self;
	}
}