<?php

namespace netPhramework\db\resources;

use netPhramework\core\LeafResource;
use netPhramework\db\common\HasRecord;

abstract class RecordProcess extends LeafResource implements RecordChild
{
	use HasRecord;
}