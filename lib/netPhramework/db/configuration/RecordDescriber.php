<?php

namespace netPhramework\db\configuration;

use netPhramework\db\mapping\Record;

interface RecordDescriber
{
	public function describe(Record $record):string;
}