<?php

namespace netPhramework\www;

use netPhramework\exchange\Request;
use netPhramework\routing\Location;
use netPhramework\routing\PathFromUri;

class WebRequest implements Request
{
	private(set) Location $location {get{
		if(!isset($this->location))
		{
			$this->location = new Location()
				->setPath(new PathFromUri($this->input->uri))
			;
			$this->location->getParameters()->merge(
				$this->input->postParameters
				?? $this->input->getParameters ?? []
			);
		}
		return $this->location;
	}}

	public bool $isToModify {get{
		return $this->input->hasPostParameters();
	}}

	public function __construct
	(
	private readonly WebRequestInput $input
	)
	{}
}
