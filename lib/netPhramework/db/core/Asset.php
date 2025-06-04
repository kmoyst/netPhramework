<?php

namespace netPhramework\db\core;

use netPhramework\core\CompositeTrait;
use netPhramework\core\Node;
use netPhramework\db\mapping\RecordSet;

class Asset implements Node
{
	use CompositeTrait;

	private RecordSet $recordSet;
	private RecordChildSet $recordNodeSet;
	private RecordSetProcessSet $recordSetProcessSet;
	private string $nodeName;

	/**
	 * @param RecordSet $recordSet
	 * @param RecordChildSet $recordNodeSet
	 * @param RecordSetProcessSet $recordSetProcessSet
	 * @param ?string $nodeName
	 */
	public function __construct(RecordSet           $recordSet,
								RecordChildSet      $recordNodeSet,
								RecordSetProcessSet $recordSetProcessSet,
								?string $nodeName = null
	)
	{
		$this->recordSet = $recordSet;
		$this->recordNodeSet = $recordNodeSet;
		$this->recordSetProcessSet = $recordSetProcessSet;
		$this->nodeName = $nodeName ?? $recordSet->getName();
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
		return $this->nodeName;
	}
}