<?php

namespace netPhramework\rendering;

use Stringable;

abstract class EncodableViewable implements Viewable, Encodable
{
	public function encode(Encoder $encoder): Stringable|string
	{
		return $encoder->encodeViewable($this);
	}
}