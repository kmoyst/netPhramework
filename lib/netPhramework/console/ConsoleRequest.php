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
		$question = "\n\nIs this a MODIFICATION request? [Y/n: default n] ";
		return readline($question) === 'Y';
	}}

	public function __construct() {}
}