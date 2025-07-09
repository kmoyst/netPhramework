<?php

namespace netPhramework\db\core;

use netPhramework\core\Node;
use netPhramework\db\mapping\Record;

interface RecordChild extends Node
{
	public function setRecord(Record $record):self;
}