<?php

namespace netPhramework\data\asset;

use netPhramework\data\record\RecordSet;
use netPhramework\nodes\Node;

interface AssetNode extends Node
{
	public function setRecordSet(RecordSet $recordSet):self;
}