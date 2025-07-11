<?php

namespace netPhramework\db\assets;

use netPhramework\db\core\Record;

interface RecordChild extends Asset
{
	public function setRecord(Record $record):self;
}