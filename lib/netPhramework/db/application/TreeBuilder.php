<?php

namespace netPhramework\db\application;

use netPhramework\core\Directory;
use netPhramework\core\Resource;
use netPhramework\db\core\RecordMapper;
use netPhramework\db\exceptions\TreeBuilderException;
use netPhramework\db\resources\NumericIdPredicate;
use netPhramework\db\resources\OneToManyLink;
use netPhramework\db\resources\RecordChild;
use netPhramework\db\resources\RecordChildSet;
use netPhramework\db\resources\RecordResource;
use netPhramework\db\resources\RecordSetChild;
use netPhramework\db\resources\RecordSetChildSet;

class TreeBuilder
{
	protected RecordChildSet $recordChildSet;
	protected RecordSetChildSet $recordSetChildSet;

	public function __construct
	(
		protected readonly RecordMapper $mapper,
		protected ?Directory $directory = null
	)
	{
		$this->reset();
	}

	private function reset():void
	{
		$this->recordChildSet 	 = new RecordChildSet();
		$this->recordSetChildSet = new RecordSetChildSet();
	}

	public function setDirectory(Directory $directory): self
	{
		$this->directory = $directory;
		return $this;
	}

	public function add(Resource $node):self
	{
		if($node instanceof RecordChild)
			$this->recordChildSet->add($node);
		elseif($node instanceof RecordSetChild)
			$this->recordSetChildSet->add($node);
		return $this;
	}

	public function oneToMany(
		RecordResourceStrategy $strategy, string $linkField):self
	{
		$node = new OneToManyLink($strategy->create($this->mapper), $linkField);
		$this->add($node);
		return $this;
	}

	public function strategy(RecordAssetStrategy $strategy):self
	{
		$this->add($strategy->create($this->mapper));
		return $this;
	}

	public function get(string $name):RecordResource
	{
		$recordSet = $this->mapper->recordsFor($name);
		$predicate = new NumericIdPredicate();
		$resource  = new RecordResource(
			$name, $recordSet, $predicate,
			$this->recordChildSet, $this->recordSetChildSet);
		$this->reset();
		return $resource;
	}

	/**
	 * @param string $name
	 * @return $this
	 * @throws TreeBuilderException
	 */
	public function commit(string $name):self
	{
		if($this->directory === null)
			throw new TreeBuilderException("Directory not set in TreeBuilder");
		$this->directory->add($this->get($name));
		return $this;
	}
}