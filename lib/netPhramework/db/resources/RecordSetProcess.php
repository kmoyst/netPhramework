<?php

namespace netPhramework\db\resources;

use netPhramework\db\traits\HasRecordSet;
use netPhramework\resources\Leaf;

abstract class RecordSetProcess extends Leaf implements RecordSetChild
{
	use HasRecordSet;
}