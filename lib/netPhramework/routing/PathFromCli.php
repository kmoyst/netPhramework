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
			echo "\nRequesting node $name ";
			for($i=1; $i<=3; $i++)
			{
				echo '.';
				usleep(300000);
			}
			system('clear');
			usleep(100000);
			echo "\nNode: $name\n";
			$names = explode('/', ltrim(rtrim($name,'.'), '/'));
			$this->setName(array_shift($names));
			if (!empty($names)) {
				$next = new PathFromArray($names);
				$this->setNext($next);
				$last = $names[count($names)-1];
				if($last !== '' && !preg_match('|\.$|',$name))
					$next->appendPath(new PathFromCli());
			} elseif(!preg_match('|\.$|',$name)) {
				$this->setNext(new PathFromCli());
			}
		}
	}

	private function query():?string
	{
		$name = readline("\nEnter node name (end with '.' to submit): ");
		return $name === '.' ? null : $name;
	}
}