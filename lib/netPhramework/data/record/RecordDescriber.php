<?php

namespace netPhramework\data\record;

interface RecordDescriber
{
	public function describe(Record $record):string;
}