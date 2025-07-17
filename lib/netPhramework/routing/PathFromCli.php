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
			$node = $this->getNodeNameFromUser();
			if($node === null)
			{
				// this will bypass the current CLI and hands over to the base
				return parent::getName();
			}
			elseif($node === '')
			{
				/**
				 * This is interpreted as a resource (leaf) as nodes(composites)
				 * cannot have an empty string as a name
				 */
				$this->setName($node);
				return parent::getName();
			}
			else
			{
				/**
				 * This will loop and build the request
				 * until an empty string representing resource (leaf)
				 * or a '.' signaling don't append anything
				 */
				echo "\nRequesting node '$node'...\n\n";
				$node = ltrim($node, '/');
				$names = explode('/', $node);
				$this->setName(array_shift($names));
				if (!empty($names)) {
					$next = new PathFromArray($names);
					$next->appendPath(new PathFromCli());
					$this->setNext($next);
				} else {
					$this->setNext(new PathFromCli());
				}
			}
		}
		return parent::getName();
	}

	private function getNodeNameFromUser():?string
	{
		$name = readline("Enter node name or enter '.' to complete request: ");
		return $name === '.' ? null : $name;
	}
}