<?php

namespace netPhramework\db\core;

use netPhramework\common\StringPredicate;
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
	private StringPredicate		$recordIdPredicate;


	public function __construct(string            $name,
								RecordSet         $recordSet,
								RecordChildSet    $recordChildSet,
								RecordSetChildSet $recordSetChildSet,
								StringPredicate   $recordIdPredicate
	)
	{
		$this->name 				= $name;
		$this->recordSet 			= $recordSet;
		$this->recordChildSet 		= $recordChildSet;
		$this->recordSetChildSet 	= $recordSetChildSet;
		$this->recordIdPredicate	= $recordIdPredicate;
	}

	public function getRecordSet(): RecordSet
	{
		return $this->recordSet;
	}

	public function getChild(string $name): Node
	{
		if($this->recordIdPredicate->test($name))
			$recordSetChild = new RecordComposite($this->recordChildSet, $name);
		else
			$recordSetChild = $this->recordSetChildSet->get($name);
		return $recordSetChild->setRecordSet($this->recordSet);
	}

	public function getName(): string
	{
		return $this->name;
	}
}