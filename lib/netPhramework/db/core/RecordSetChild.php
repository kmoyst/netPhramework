<?php

namespace netPhramework\db\core;

use netPhramework\db\mapping\RecordSet;

interface RecordSetChild extends AssetNode
{
	public function setRecordSet(RecordSet $recordSet):self;
}