<?php

namespace netPhramework\data\core;

use netPhramework\common\StringIterator;
use netPhramework\data\abstraction\Database;

readonly class RecordMapper implements RecordAccess, RecordSetFactory
{
	public function __construct
	(
		private Database $database,
		public RecordMapSet $mapSet = new RecordMapSet()
	)
	{}

	public function addMap(RecordMap $map):self
	{
		$this->mapSet->add($map);
		return $this;
	}

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
		$rsName = $this->mapSet->get($name) ?? $name;
		$schema = $this->database->getSchema($rsName);
		$table 	= $this->database->getTable($rsName);
		return new RecordSet($schema, $table);
	}

	public function listAllRecordSets():StringIterator
	{
		return new StringIterator($this->database->listTables());
	}
}