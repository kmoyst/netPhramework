<?php

namespace netPhramework\db\resources;

use netPhramework\db\traits\HasRecord;
use netPhramework\resources\Leaf;

abstract class RecordProcess extends Leaf implements RecordChild
{
	use HasRecord;
}