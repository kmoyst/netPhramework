<?php

namespace netPhramework\db\resources;

use netPhramework\core\Leaf;

abstract class RecordSetProcess extends Leaf implements RecordSetChild
{
	use HasRecordSet;
}