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
	private RecordChildSet 		$recordChildSet;
	private RecordSetChildSet 	$recordSetChildSet;

	/**
	 * @param string $name
	 * @param RecordSet $recordSet
	 * @param RecordChildSet $recordChildSet
	 * @param RecordSetChildSet $recordSetChildSet
	 */
	public function __construct(string            $name,
								RecordSet         $recordSet,
								RecordChildSet    $recordChildSet,
								RecordSetChildSet $recordSetChildSet,

	)
	{
		$this->name 				= $name;
		$this->recordSet 			= $recordSet;
		$this->recordChildSet 		= $recordChildSet;
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
				->setNodeSet($this->recordChildSet)
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