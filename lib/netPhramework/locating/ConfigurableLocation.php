<?php

namespace netPhramework\locating;

use netPhramework\common\Variables;

interface ConfigurableLocation extends MutableLocation
{
	public function setParameters(Variables|array|null $parameters): self;
}