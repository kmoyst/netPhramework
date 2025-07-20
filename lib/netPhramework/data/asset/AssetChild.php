<?php

namespace netPhramework\data\asset;

use netPhramework\data\record\RecordSet;
use netPhramework\nodes\Node;

interface AssetChild extends Node
{
	public function setRecordSet(RecordSet $recordSet):self;
}