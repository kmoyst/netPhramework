<?php

namespace netPhramework\db\nodes;

use netPhramework\resources\Resource;

abstract class RecordProcess extends Resource implements RecordChild
{
	use HasRecord;
}