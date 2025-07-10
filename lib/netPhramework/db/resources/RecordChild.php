<?php

namespace netPhramework\db\resources;

use netPhramework\db\core\Record;

interface RecordChild extends Asset
{
	public function setRecord(Record $record):self;
}