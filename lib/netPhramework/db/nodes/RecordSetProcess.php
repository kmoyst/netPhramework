<?php

namespace netPhramework\db\nodes;

use netPhramework\resources\Resource;

abstract class RecordSetProcess extends Resource implements RecordSetChild
{
	use HasRecordSet;
}