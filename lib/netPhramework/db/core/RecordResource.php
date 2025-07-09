<?php

namespace netPhramework\db\core;

use netPhramework\core\Composite;
use netPhramework\core\Node;
use netPhramework\db\mapping\RecordSet;
use netPhramework\db\core\RecordSetChildSet as setChildSet;
use netPhramework\common\StringPredicate as Predicate;

class RecordResource extends Composite
{
	public function __construct
	(
	public readonly string $name,
	public readonly RecordSet $recordSet,
	public readonly Predicate $recordIdPredicate   = new NumericIdPredicate(),
	public readonly RecordChildSet $recordChildSet = new RecordChildSet(),
	public readonly setChildSet $recordSetChildSet = new RecordSetChildSet()
	) {}

	public function getName(): string
	{
		return $this->name;
	}

	public function getChild(string $id): Node
	{
		return $this->resolveChild($id)->setRecordSet($this->recordSet);
	}

	/**
	 * @param string $id
	 * @return RecordSetChild
	 */
	private function resolveChild(string $id): RecordSetChild
	{
		if($this->recordIdPredicate->test($id))
			return new RecordComposite($this->recordChildSet, $id);
		else
			return $this->recordSetChildSet->get($id);
	}
}