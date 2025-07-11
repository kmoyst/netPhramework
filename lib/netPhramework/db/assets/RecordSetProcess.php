<?php

namespace netPhramework\db\assets;

use netPhramework\db\traits\HasRecordSet;
use netPhramework\resources\Leaf;

abstract class RecordSetProcess extends Leaf implements RecordSetChild
{
	use HasRecordSet;
}