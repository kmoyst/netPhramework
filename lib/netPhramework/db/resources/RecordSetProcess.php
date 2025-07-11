<?php

namespace netPhramework\db\resources;

use netPhramework\core\Leaf;
use netPhramework\db\traits\HasRecordSet;

abstract class RecordSetProcess extends Leaf implements RecordSetChild
{
	use HasRecordSet;
}