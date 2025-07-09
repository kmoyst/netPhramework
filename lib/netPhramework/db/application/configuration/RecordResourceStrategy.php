<?php

namespace netPhramework\db\application\configuration;

use netPhramework\db\application\mapping\RecordMapper;
use netPhramework\db\core\RecordResource;

interface RecordResourceStrategy
{
	public function create(RecordMapper $mapper):RecordResource;
}