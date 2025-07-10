<?php

namespace netPhramework\db\resources;

use netPhramework\core\LeafResource;
use netPhramework\db\common\HasRecordSet;

abstract class RecordSetProcess extends LeafResource implements RecordSetChild
{
	use HasRecordSet;
}