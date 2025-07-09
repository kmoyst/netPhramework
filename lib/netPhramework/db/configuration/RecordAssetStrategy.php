<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\RecordAsset;
use netPhramework\db\mapping\RecordMapper;

interface RecordAssetStrategy
{
	public function create(RecordMapper $mapper):RecordAsset;
}