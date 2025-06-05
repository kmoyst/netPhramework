<?php

namespace netPhramework\rendering;

use Stringable;

interface Encodable
{
	public function encode(Encoder $encoder):Stringable|string;
}