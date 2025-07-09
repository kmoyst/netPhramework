<?php

namespace netPhramework\db\resources;

use netPhramework\db\core\RecordSet;

interface RecordSetChild extends RecordAsset
{
	public function setRecordSet(RecordSet $recordSet):self;
}