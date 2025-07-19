<?php

namespace netPhramework\console;

use netPhramework\exchange\Request;
use netPhramework\routing\Location;
use netPhramework\routing\PathFromCli;

class ConsoleRequest implements Request
{
	protected(set) Location $location {get{
		if(!isset($this->location))
		{
			$this->location = new Location()
				->setPath(new PathFromCli());
		}
		return $this->location;
	}}

	public bool $isToModify {get{
		system('clear');
		$question = "\n\nIs this a MODIFICATION request? [Y/n: default n] ";
		$answer = readline($question);
		system('clear');
		return $answer === 'Y';
	}}

	public function __construct() {}
}