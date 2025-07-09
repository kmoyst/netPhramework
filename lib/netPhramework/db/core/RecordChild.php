<?php

namespace netPhramework\db\core;

use netPhramework\db\mapping\Record;

interface RecordChild extends RecordAsset
{
	public function setRecord(Record $record):self;
}