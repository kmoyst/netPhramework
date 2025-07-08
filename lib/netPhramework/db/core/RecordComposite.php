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
	 * @param string $id
	 * @return Node
	 * @throws MappingException
	 * @throws NodeNotFound
	 */
	public function getChild(string $id): Node
	{
		$record = $this->recordSet->getRecord($this->recordId);
		return $this->nodeSet->get($id)->setRecord($record);
	}

	public function getName(): string
	{
		return $this->recordId;
	}
}