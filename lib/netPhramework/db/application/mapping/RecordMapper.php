<?php

namespace netPhramework\db\application\mapping;

use netPhramework\db\abstraction\Database;
use netPhramework\db\mapping\RecordSet;

readonly class RecordMapper implements RecordAccess, RecordSetFactory
{
	public function __construct
	(
		private Database $database
	)
	{}

	public function lookupFor(string $name): RecordLookup
	{
		return new RecordLookup($this->recordsFor($name));
	}

	public function finderFor(string $name): RecordFinder
	{
		return new RecordFinder($this->recordsFor($name));
	}

	public function optionsFor(
		string $name, RecordDescriber $describer): RecordOptions
	{
		return new RecordOptions($this->recordsFor($name), $describer);
	}

	public function recordsFor(string $name):RecordSet
	{
		$schema = $this->database->getSchema($name);
		$table 	= $this->database->getTable($name);
		return new RecordSet($schema, $table);
	}

	public function listAllRecordSets():array
	{
		return $this->database->listTables();
	}
}