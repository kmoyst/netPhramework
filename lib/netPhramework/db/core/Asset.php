<?php

namespace netPhramework\db\core;

use netPhramework\core\CompositeTrait;
use netPhramework\core\Node;
use netPhramework\db\mapping\RecordSet;

class Asset implements Node
{
	use CompositeTrait;

	private RecordSet $recordSet;
	private RecordNodeSet $recordNodeSet;
	private RecordSetNodeSet $recordSetProcessSet;

	/**
	 * @param RecordSet $recordSet
	 * @param RecordNodeSet $recordNodeSet
	 * @param RecordSetNodeSet $recordSetProcessSet
	 */
	public function __construct(RecordSet        $recordSet,
								RecordNodeSet    $recordNodeSet,
								RecordSetNodeSet $recordSetProcessSet)
	{
		$this->recordSet = $recordSet;
		$this->recordNodeSet = $recordNodeSet;
		$this->recordSetProcessSet = $recordSetProcessSet;
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
		return $this->recordSet->getName();
	}
}