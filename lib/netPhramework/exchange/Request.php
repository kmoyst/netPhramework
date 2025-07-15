<?php

namespace netPhramework\exchange;

use netPhramework\routing\Location;

abstract class Request extends Location
{
	abstract public function isModificationRequest():bool;
}