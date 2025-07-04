<?php

namespace netPhramework\db\core;

use netPhramework\core\CompositeTrait;
use netPhramework\core\Node;
use netPhramework\db\exceptions\MappingException;
use netPhramework\exceptions\NodeNotFound;

class RecordComposite extends RecordSetChild
{
	use CompositeTrait;

	public function __construct(
		private readonly RecordChildSet $nodeSet,
		private readonly string $recordId
	) {}

	/**
	 * @param string $name
	 * @return Node
	 * @throws MappingException
	 * @throws NodeNotFound
	 */
	public function getChild(string $name): Node
	{
		$node = $this->nodeSet->get($name);
		$node->setRecord($this->recordSet->getRecord($this->recordId));
		return $node;
	}

	public function getName(): string
	{
		return $this->recordId;
	}
}