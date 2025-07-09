<?php

namespace netPhramework\db\resources;

use netPhramework\db\core\Record;

interface RecordChild extends RecordAsset
{
	public function setRecord(Record $record):self;
}