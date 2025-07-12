<?php

namespace netPhramework\db\resources;

use netPhramework\resources\Leaf;

abstract class RecordProcess extends Leaf implements RecordChild
{
	use HasRecord;
}