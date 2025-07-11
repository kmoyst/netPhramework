<?php

namespace netPhramework\db\assets;

use netPhramework\db\core\RecordSet;

interface RecordSetChild extends Asset
{
	public function setRecordSet(RecordSet $recordSet):self;
}