<?php

namespace netPhramework\db\core;

use netPhramework\core\Composite;
use netPhramework\core\Node;
use netPhramework\core\NodeSet;
use netPhramework\db\abstraction\Database;
use netPhramework\db\configuration\RecordDescriber;
use netPhramework\db\configuration\RecordFinder;
use netPhramework\db\configuration\RecordLookup;
use netPhramework\db\configuration\RecordMapper;
use netPhramework\db\configuration\RecordOptions;
use netPhramework\db\mapping\RecordSet;

class Application extends Composite implements RecordMapper
{
	private NodeSet $nodeSet;

	public function __construct
	(
	private readonly string $name,
	private readonly Database $database
	)
	{}

	public function getChild(string $id): Node
	{
		return $this->nodeSet->get($id);
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function add(Asset $asset):self
	{
		$this->nodeSet->add($asset);
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
		$schema = $this->database->getSchema($name);
		$table 	= $this->database->getTable($name);
		return new RecordSet($schema, $table);
	}

	public function listAllRecordSets():array
	{
		return $this->database->listTables();
	}
}