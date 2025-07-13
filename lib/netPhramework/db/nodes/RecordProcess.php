<?php

namespace netPhramework\db\nodes;

use netPhramework\nodes\Resource;

abstract class RecordProcess extends Resource implements RecordChild
{
	use HasRecord;
}