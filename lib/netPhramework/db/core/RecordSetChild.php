<?php

namespace netPhramework\db\core;

use netPhramework\core\Node;
use netPhramework\db\mapping\RecordSet;

interface RecordSetChild extends Node
{
	public function setRecordSet(RecordSet $recordSet):self;
}