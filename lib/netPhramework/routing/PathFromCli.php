<?php

namespace netPhramework\routing;

use netPhramework\exceptions\PathException;

class PathFromCli extends Path
{
	/**
	 * @return string|null
	 * @throws PathException
	 */
	public function getName():?string
	{
		if (parent::getName() === null) {
			if (($node = $this->getNodeNameFromUser()) !== null) {
				$node = ltrim($node, '/ ');
				$names = explode('/', $node);
				$this->setName(array_shift($names));
				if (!empty($names)) {
					$next = new PathFromArray($names);
					$next->appendPath(new PathFromCli());
					$this->setNext($next);
				} else {
					$this->setNext(new PathFromCli());
				}
				echo "\nRequesting node '$node'...\n\n";
			} else {
				$name = readline("Resource name? (blank for default): ");
				$this->setName($name);
				if ($name !== '')
					echo "\nRequesting resource $name...\n\n";
				else
					echo "\nRequesting default resource...\n\n";
				//$this->setNext(null);
			}
		}
		return parent::getName();
	}

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