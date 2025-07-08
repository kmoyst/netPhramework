<?php

namespace netPhramework\db\core;

use netPhramework\common\StringPredicate;
use netPhramework\core\CompositeTrait;
use netPhramework\core\Node;
use netPhramework\db\mapping\RecordSet;
use netPhramework\exceptions\NodeNotFound;

class Asset extends Node
{
	use CompositeTrait;

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

	public function getChild(string $name): Node
	{
		return $this->resolveChild($name)->setRecordSet($this->recordSet);
	}

	/**
	 * @param string $name
	 * @return RecordSetChild
	 * @throws NodeNotFound
	 */
	private function resolveChild(string $name): RecordSetChild
	{
		if($this->recordIdPredicate->test($name))
			return new RecordComposite($this->recordChildSet, $name);
		else
			return $this->recordSetChildSet->get($name);
	}
}