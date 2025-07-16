<?php

namespace netPhramework\http;

use netPhramework\exchange\Request;
use netPhramework\routing\Location;

class HttpRequest implements Request
{
	private(set) Location $location {get{
		if(!isset($this->location))
			$this->location = new LocationFromHttpInput($this->input);
		return $this->location;
	}set{}}

	private(set) bool $isModificationRequest {get{
		return $this->input->hasPostParameters();
	}set{}}

	public function __construct
	(
	private HttpInput $input = new HttpInput()
	)
	{}
}
