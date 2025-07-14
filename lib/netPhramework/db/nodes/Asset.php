<?php

namespace netPhramework\db\nodes;

use netPhramework\common\IsNumeric;
use netPhramework\common\StringPredicate as Predicate;
use netPhramework\db\nodes\RecordSetChildSet as setChildSet;
use netPhramework\db\core\RecordSet;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\Composite;
use netPhramework\nodes\Node;

class Asset extends Composite implements AssetResourceDepot
{
	public function __construct
	(
	public readonly string $name,
	public readonly RecordSet $recordSet,
	public readonly Predicate $recordIdPredicate   = new IsNumeric(),
	public readonly RecordChildSet $recordChildSet = new RecordChildSet(),
	public readonly setChildSet $recordSetChildSet = new setChildSet()
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
	 * @throws NodeNotFound
	 */
	private function resolveChild(string $id): RecordSetChild
	{
		if($this->recordIdPredicate->test($id))
			return new RecordComposite($this->recordChildSet, $id);
		else
			return $this->recordSetChildSet->get($id);
	}
}