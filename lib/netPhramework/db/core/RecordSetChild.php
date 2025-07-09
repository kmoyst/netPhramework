<?php

namespace netPhramework\db\core;

use netPhramework\db\mapping\RecordSet;

interface RecordSetChild extends RecordAsset
{
	public function setRecordSet(RecordSet $recordSet):self;
}