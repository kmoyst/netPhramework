<?php

namespace netPhramework\db\mapping;

interface RecordDescriber
{
	public function describe(Record $record):string;
}