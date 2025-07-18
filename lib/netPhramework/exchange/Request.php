<?php

namespace netPhramework\exchange;

use netPhramework\routing\Location;

interface Request
{
	public Location $location {get;}
	public bool $isModificationRequest {get;}
}