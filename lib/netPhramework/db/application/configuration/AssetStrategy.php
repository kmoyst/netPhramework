<?php

namespace netPhramework\db\application\configuration;

use netPhramework\db\application\mapping\RecordMapper;
use netPhramework\db\core\Asset;

interface AssetStrategy
{
	public function create(RecordMapper $mapper):Asset;
}