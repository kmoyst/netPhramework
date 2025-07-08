<?php

namespace netPhramework\db\core;

use netPhramework\core\Composite;
use netPhramework\core\Node;
use netPhramework\core\NodeSet;
use netPhramework\db\configuration\RecordMapper;

class Application extends Composite
{
	private NodeSet $nodeSet;
	private RecordMapper $mapper;

	public function __construct
	(
		private readonly string $name
	)
	{}

	public function newAsset(string $name):Asset
	{
		$recordSet 	= $this->mapper->recordsFor($name);
		$asset 		= new Asset($name, $recordSet);
		$this->nodeSet->add($asset);
		return $asset;
	}

	public function getChild(string $id): Node
	{
		return $this->nodeSet->get($id);
	}

	public function getName(): string
	{
		return $this->name;
	}
}