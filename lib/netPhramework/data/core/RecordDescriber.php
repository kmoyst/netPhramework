<?php

namespace netPhramework\data\core;

interface RecordDescriber
{
	public function describe(Record $record):string;
}