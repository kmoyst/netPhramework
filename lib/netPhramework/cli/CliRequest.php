<?php

namespace netPhramework\cli;

use netPhramework\core\Environment;
use netPhramework\exchange\Request;
use netPhramework\routing\Location;

class CliRequest implements Request
{
	public function __construct(private readonly Environment $environment) {}

	private(set) Location $location {get{
		if(!isset($this->location))
			$this->location = new LocationFromCli();
		return $this->location;
	}set{}}

	private(set) bool $isModificationRequest {get{
		$question = "\n\nIs this a modification request? [Y/n: default n] ";
		return readline($question) === 'Y';
	}set{}}
}