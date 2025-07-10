<?php

namespace netPhramework\db\resources;

use netPhramework\core\Leaf;
use netPhramework\db\common\HasRecord;

abstract class RecordProcess extends Leaf implements RecordChild
{
	use HasRecord;
}