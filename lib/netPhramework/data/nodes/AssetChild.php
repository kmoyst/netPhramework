<?php

namespace netPhramework\data\nodes;

use netPhramework\data\core\RecordSet;
use netPhramework\nodes\Node;

interface AssetChild extends Node
{
	public function setRecordSet(RecordSet $recordSet):self;
}