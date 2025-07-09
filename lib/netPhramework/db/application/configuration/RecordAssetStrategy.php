<?php

namespace netPhramework\db\application\configuration;

use netPhramework\db\application\mapping\RecordMapper;
use netPhramework\db\core\RecordAsset;

interface RecordAssetStrategy
{
	public function create(RecordMapper $mapper):RecordAsset;
}