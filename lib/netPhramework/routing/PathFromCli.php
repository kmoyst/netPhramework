<?php

namespace netPhramework\routing;

use netPhramework\exceptions\PathException;

class PathFromCli extends PathTemplate
{
	/**
	 * @throws PathException
	 */
	protected function parse():void
	{
		if(($name = $this->query()) === '')
		{
			/**
			 * This is interpreted as a resource (leaf) as nodes(composites)
			 * cannot have an empty string as a name
			 */
			$this->setName($name);
			$this->setNext(null);
		}
		elseif($name !== null)
		{
			/**
			 * This will loop and build the request
			 * until an empty string representing resource (leaf)
			 * or a '.' signaling don't append anything
			 */
			echo "\nRequesting node '$name'...\n\n";
			$trimmed = ltrim($name, '/');
			$names = explode('/', $trimmed);
			$this->setName(array_shift($names));
			if (!empty($names)) {
				$next = new PathFromArray($names);
				$this->setNext($next);
				/**
				 * Very important
				 * Don't append a CLI query if
				 * there is a uri form entry with a trailing slash
				 * - that indicates the user requested a resource (leaf)
				 */
				if($names[count($names)-1] !== '')
					$next->appendPath(new PathFromCli());
			} else {
				$this->setNext(new PathFromCli());
			}
		}
		// if null is returned, don't do anything
	}

	private function query():?string
	{
		$name = readline("Enter node name or enter '.' to complete request: ");
		return $name === '.' ? null : $name;
	}
}