<?php

namespace netPhramework\db\nodes;

use netPhramework\db\core\RecordSet;
use netPhramework\nodes\Node;

interface AssetChild extends Node
{
	public function setRecordSet(RecordSet $recordSet):self;
}