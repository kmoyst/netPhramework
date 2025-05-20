<?php

namespace netPhramework\rendering;

interface Encodable
{
	public function encode(Encoder $encoder):string|array;
}