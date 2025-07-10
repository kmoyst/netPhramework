<?php

namespace netPhramework\db\resources;

use netPhramework\core\CompositeResource;
use netPhramework\core\Resource;
use netPhramework\db\core\Record;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Condition;

class OneToManyLink extends CompositeResource implements RecordChild
{
	public function __construct
	(
	private readonly RecordResource $recordSetComposite,
	private readonly string         $linkField
	)
	{}

	/**
	 * @param Record $record
	 * @return RecordChild
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function setRecord(Record $record): RecordChild
	{
		$childRecords = $this->recordSetComposite->recordSet;
		$field = $childRecords->getField($this->linkField);
		$condition = new Condition()
			->setField($field)
			->setValue($record->getId())
		;
		$childRecords->reset()->addCondition($condition);
		return $this;
	}

	public function getChild(string $id): Resource
	{
		return $this->recordSetComposite->getChild($id);
	}

	public function getName(): string
	{
		return $this->recordSetComposite->getName();
	}
}