<?php

namespace netPhramework\data\nodes;

use netPhramework\data\core\RecordSet;
use netPhramework\nodes\Node;

interface RecordSetNode extends Node
{
	public function setRecordSet(RecordSet $recordSet):self;
}