<?php

namespace netPhramework\cli;

use netPhramework\routing\Path;
use netPhramework\routing\PathFromArray;

class PathFromCli extends Path
{
	public ?string $name {get{
		if(!isset($this->name))
		{
			if(($node = $this->getNodeNameFromUser()) !== null)
			{
				$node = ltrim($node, '/ ');
				$names = explode('/', $node);
				$this->name = array_shift($names);
				if(!empty($names))
				{
					$path = new Path();
					$path->appendPath(new PathFromArray($names));
					$path->appendPath(new PathFromCli());
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
	}}

	private function getNodeNameFromUser():?string
	{
		$name = readline("Enter node or '.' to specify resource: ");
		if($name === '')
		{
			echo "Node cannot be empty\r\n";
			return $this->getNodeNameFromUser();
		}
		elseif($name === '.') return null;
		else return $name;
	}
}