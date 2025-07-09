<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\RecordResource;
use netPhramework\db\mapping\RecordMapper;

interface RecordResourceStrategy
{
	public function create(RecordMapper $mapper):RecordResource;
}