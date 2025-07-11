<?php

namespace netPhramework\db\assets;

use netPhramework\db\traits\HasRecord;
use netPhramework\resources\Leaf;

abstract class RecordProcess extends Leaf implements RecordChild
{
	use HasRecord;
}