<?php

namespace netPhramework\data\nodes;

use netPhramework\data\core\Record;
use netPhramework\data\exceptions\FieldAbsent;
use netPhramework\data\exceptions\MappingException;
use netPhramework\data\mapping\Condition;
use netPhramework\nodes\Composite;
use netPhramework\nodes\Node;

class RecordBranch extends Composite implements RecordNode
{
	public function __construct
	(
	private readonly RecordSetComposite $composite,
	private readonly string             $linkField
	)
	{}

	/**
	 * @param Record $record
	 * @return RecordNode
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function setRecord(Record $record): RecordNode
	{
		$childRecords = $this->composite->recordSet;
		$field = $childRecords->getField($this->linkField);
		$condition = new Condition()
			->setField($field)
			->setValue($record->getId())
		;
		$childRecords->reset()->addCondition($condition);
		return $this;
	}

	public function getChild(string $id): Node
	{
		return $this->composite->getChild($id);
	}

	public function getName(): string
	{
		return $this->composite->getName();
	}
}