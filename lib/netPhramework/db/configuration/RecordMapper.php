<?php

namespace netPhramework\db\configuration;

use netPhramework\db\abstraction\Database;
use netPhramework\db\mapping\RecordSet;

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

	public function optionsFor(
		string $name, RecordDescriber $describer): RecordOptions
	{
		return new RecordOptions($this->recordsFor($name), $describer);
	}

	public function recordsFor(string $name):RecordSet
	{
		$schema = $this->database->getSchema($name);
		$table 	= $this->database->getTable($name);
		return new RecordSet($name, $schema, $table);
	}

	public function oneToManyFor(string $name, string $linkField):OneToMany
	{
		return new OneToMany($linkField, $this->recordsFor($name));
	}

    public function listAllRecordSets():array
    {
        return $this->database->listTables();
    }
}