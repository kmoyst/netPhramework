<?php

namespace netPhramework\cli;

use netPhramework\core\Environment;
use netPhramework\exchange\Request;
use netPhramework\routing\Location;
use netPhramework\routing\LocationFromCli;

class CliRequest implements Request
{
	private(set) Location $location {get{
		if(!isset($this->location))
			$this->location = new LocationFromCli();
		return $this->location;
	}}

	private(set) bool $isModificationRequest {get{
		$question = "\n\nIs this a modification request? [Y/n: default n] ";
		return readline($question) === 'Y';
	}}

	public function __construct(private readonly Environment $environment) {}
}