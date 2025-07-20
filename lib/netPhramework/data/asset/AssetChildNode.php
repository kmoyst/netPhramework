<?php

namespace netPhramework\data\asset;

use netPhramework\data\record\Record;
use netPhramework\nodes\Node;

interface AssetChildNode extends Node
{
	public function setRecord(Record $record):self;
}