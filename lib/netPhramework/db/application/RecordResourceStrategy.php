<?php

namespace netPhramework\db\application;

use netPhramework\db\core\RecordMapper;
use netPhramework\db\resources\RecordResource;

interface RecordResourceStrategy
{
	public function create(RecordMapper $mapper):RecordResource;
}