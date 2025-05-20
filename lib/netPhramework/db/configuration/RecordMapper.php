<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\RecordSet;
use netPhramework\db\mapping\Database;

readonly class RecordMapper implements RecordAccess
{
    public function __construct(private Database $database) {}

	public function lookupFor(string $name): RecordLookup
	{
		return new RecordLookup($this->recordsFor($name));
	}

	public function finderFor(string $name): RecordFinder
	{
		return new RecordFinder($this->recordsFor($name));
	}

	public function recordsFor(string $name):RecordSet
	{
		$schema = $this->database->getSchema($name);
		$table 	= $this->database->getTable($name);
		return new RecordSet($name, $schema, $table);
	}
}