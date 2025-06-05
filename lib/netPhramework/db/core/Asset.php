<?php

namespace netPhramework\db\core;

use netPhramework\core\CompositeTrait;
use netPhramework\core\Node;
use netPhramework\db\mapping\RecordSet;

class Asset implements Node
{
	use CompositeTrait;

	private string $name;
	private RecordSet $recordSet;
	private RecordChildSet $recordNodeSet;
	private RecordSetProcessSet $recordSetProcessSet;

	/**
	 * @param RecordSet $recordSet
	 * @param RecordChildSet $recordNodeSet
	 * @param RecordSetProcessSet $recordSetProcessSet
	 * @param ?string $name
	 */
	public function __construct(RecordSet           $recordSet,
								RecordChildSet      $recordNodeSet,
								RecordSetProcessSet $recordSetProcessSet,
								?string $name = null
	)
	{
		$this->recordSet = $recordSet;
		$this->recordNodeSet = $recordNodeSet;
		$this->recordSetProcessSet = $recordSetProcessSet;
		$this->name = $name ?? $recordSet->getName();
	}

	public function getRecordSet(): RecordSet
	{
		return $this->recordSet;
	}

	public function getChild(string $name): Node
	{
		if(is_numeric($name))
		{
			return new RecordComposite()
				->setRecord($this->recordSet->getRecord($name))
				->setNodeSet($this->recordNodeSet)
				;
		}
		else
		{
			return $this->recordSetProcessSet->get($name)
				->setRecordSet($this->recordSet)
				;
		}
	}

	public function getName(): string
	{
		return $this->name;
	}
}