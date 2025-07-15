<?php

namespace netPhramework\cli;

use netPhramework\exchange\Input;
use netPhramework\routing\Location;

class CliInput implements Input
{
	public function isModificationRequest():bool
	{
		$question = "Is this a modification request? [Y/n: default n] ";
		return readline($question) === 'Y';
	}

	public function getLocation(): Location
	{
		return new LocationFromCli();
	}
}