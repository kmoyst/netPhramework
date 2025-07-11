<?php

namespace netPhramework\db\resources;

use netPhramework\core\Leaf;
use netPhramework\db\traits\HasRecord;

abstract class RecordProcess extends Leaf implements RecordChild
{
	use HasRecord;
}