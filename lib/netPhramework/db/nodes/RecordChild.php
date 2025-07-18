<?php

namespace netPhramework\db\nodes;

use netPhramework\db\core\Record;
use netPhramework\nodes\Node;

interface RecordChild extends Node
{
	public function setRecord(Record $record):self;
}