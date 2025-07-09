<?php

namespace netPhramework\db\application;

use netPhramework\db\core\RecordMapper;
use netPhramework\db\resources\RecordAsset;

interface RecordAssetStrategy
{
	public function create(RecordMapper $mapper):RecordAsset;
}