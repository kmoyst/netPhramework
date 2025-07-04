<?php

namespace netPhramework\db\core;

use netPhramework\common\StringPredicate;
use netPhramework\core\CompositeTrait;
use netPhramework\core\Node;
use netPhramework\db\mapping\RecordSet;
use netPhramework\exceptions\NodeNotFound;

readonly class Asset implements Node
{
	use CompositeTrait;

	public function __construct(
		private string $name,
		private RecordSet $recordSet,
		private RecordChildSet $recordChildSet,
		private RecordSetChildSet $recordSetChildSet,
		private StringPredicate $recordIdPredicate
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