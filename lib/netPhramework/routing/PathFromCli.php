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
		if(($name = $this->query()) === null)
		{
			return;
		}
		elseif($name === '')
		{
			$this->setName($name);
			$this->setNext(null);
		}
		else
		{
			echo "\nRequesting node $name ...\n\n";
			$names = explode('/', ltrim($name, '/'));
			$this->setName(array_shift($names));
			if (!empty($names)) {
				$next = new PathFromArray($names);
				$this->setNext($next);
				if($names[count($names)-1] !== '')
					$next->appendPath(new PathFromCli());
			} else {
				$this->setNext(new PathFromCli());
			}
		}
	}

	private function query():?string
	{
		$name = readline("\nEnter node name or enter '.' to complete request: ");
		return $name === '.' ? null : $name;
	}
}