<?php

namespace netPhramework\db\nodes;

use netPhramework\db\exceptions\MappingException;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\resources\Composite;
use netPhramework\resources\Node;

class RecordComposite extends Composite implements RecordSetChild
{
	use HasRecordSet;

	public function __construct(
		private readonly RecordChildSet $childSet,
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
		return $this->childSet->get($id)->setRecord($record);
	}

	public function getName(): string
	{
		return $this->recordId;
	}
}