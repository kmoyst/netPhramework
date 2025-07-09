<?php

namespace netPhramework\db\application\mapping;

use netPhramework\db\mapping\Record;

interface RecordDescriber
{
	public function describe(Record $record):string;
}