<?php

namespace netPhramework\db\core;

use netPhramework\common\StringPredicate;
use netPhramework\core\Composite;
use netPhramework\core\Node;
use netPhramework\db\mapping\RecordSet;
use netPhramework\exceptions\NodeNotFound;

class Asset extends Composite
{
	public function __construct(
		private readonly string $name,
		private readonly RecordSet $recordSet,
		private readonly RecordChildSet $recordChildSet,
		private readonly RecordSetChildSet $recordSetChildSet,
		private readonly StringPredicate $recordIdPredicate
	) {}

	public function getName(): string
	{
		return $this->name;
	}

	public function getRecordSet(): RecordSet
	{
		return $this->recordSet;
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