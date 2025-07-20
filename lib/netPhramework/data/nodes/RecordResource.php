<?php

namespace netPhramework\data\nodes;

use netPhramework\nodes\Resource;

abstract class RecordResource extends Resource
{
	abstract public function enlist(RecordResourceRegistry $registry):void;
}