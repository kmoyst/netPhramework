<?php

namespace netPhramework\db\core;

use netPhramework\core\Leaf;

abstract class RecordSetProcess extends Leaf implements RecordSetChild
{
	use HasRecordSet;
}