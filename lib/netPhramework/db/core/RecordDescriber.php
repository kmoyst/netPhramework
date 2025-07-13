<?php

namespace netPhramework\db\core;

interface RecordDescriber
{
	public function describe(Record $record):string;
}