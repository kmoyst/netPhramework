<?php

namespace netPhramework\db\core;

use netPhramework\core\CompositeTrait;
use netPhramework\core\Node;
use netPhramework\db\mapping\RecordSet;

class Asset implements Node
{
	use CompositeTrait;

	private string 				$name;
	private RecordSet 			$recordSet;
	private RecordChildSet 		$recordNodeSet;
	private RecordSetChildSet 	$recordSetChildSet;

	/**
	 * @param string $name
	 * @param RecordSet $recordSet
	 * @param RecordChildSet $recordNodeSet
	 * @param RecordSetChildSet $recordSetChildSet
	 */
	public function __construct(string            $name,
								RecordSet         $recordSet,
								RecordChildSet    $recordNodeSet,
								RecordSetChildSet $recordSetChildSet,

	)
	{
		$this->name 				= $name;
		$this->recordSet 			= $recordSet;
		$this->recordNodeSet 		= $recordNodeSet;
		$this->recordSetChildSet 	= $recordSetChildSet;
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
			return $this->recordSetChildSet->get($name)
				->setRecordSet($this->recordSet)
				;
		}
	}

	public function getName(): string
	{
		return $this->name;
	}
}