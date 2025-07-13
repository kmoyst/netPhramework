<?php

namespace netPhramework\db\nodes;

use netPhramework\nodes\Resource;

abstract class RecordSetProcess extends Resource implements RecordSetChild
{
	use HasRecordSet;
}