<?php

namespace netPhramework\db\resources;

use netPhramework\core\Leaf;

abstract class RecordProcess extends Leaf implements RecordChild
{
	use HasRecord;
}