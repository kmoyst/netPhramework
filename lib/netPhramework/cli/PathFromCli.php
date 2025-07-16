<?php

namespace netPhramework\cli;

use netPhramework\routing\MutablePath;
use netPhramework\routing\PathFromArray;

class PathFromCli extends MutablePath
{
	private string $name;
	private ?MutablePath $next;

	public function getName(): string
	{
		if(!isset($this->name))
		{
			if(($node = $this->getNode()) !== null)
			{
				$node = ltrim($node, '/ ');
				$names = explode('/', $node);
				$this->name = array_shift($names);
				if(!empty($names))
				{
					$path = new MutablePath();
					$path->appendMutablePath(new PathFromArray($names));
					$path->appendMutablePath(new PathFromCli());
					$this->next = $path;
				}
				else
				{
					$this->next = new PathFromCli();
				}
				echo "\nRequesting node '$node'...\n\n";
			} else {
				$this->next = null;
				$this->name = readline("Resource name? (blank for default): ");
				if($this->name !== '')
					echo "\nRequesting resource '$this->name'...\n\n";
				else
					echo "\nRequesting default resource...\n\n";
			}
		}
		return $this->name;
	}

	public function getNext(): ?MutablePath
	{
		return $this->next;
	}

	private function getNode():?string
	{
		$name = readline("Enter node or '.' to specify resource: ");
		if($name === '')
		{
			echo "Node cannot be empty\r\n";
			return $this->getNode();
		}
		elseif($name === '.') return null;
		else return $name;
	}
}