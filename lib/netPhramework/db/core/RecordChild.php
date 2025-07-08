<?php

namespace netPhramework\db\core;

use netPhramework\db\mapping\Record;

interface RecordChild extends AssetNode
{
	public function setRecord(Record $record):self;
}