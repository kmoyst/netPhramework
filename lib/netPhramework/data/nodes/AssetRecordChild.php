<?php

namespace netPhramework\data\nodes;

use netPhramework\data\core\Record;
use netPhramework\nodes\Node;

interface AssetRecordChild extends Node
{
	public function setRecord(Record $record):self;
}