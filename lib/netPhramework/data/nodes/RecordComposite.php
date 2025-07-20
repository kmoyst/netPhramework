<?php

namespace netPhramework\data\nodes;

use netPhramework\data\exceptions\MappingException;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\Composite;
use netPhramework\nodes\Node;

class RecordComposite extends Composite implements RecordSetNode
{
	use HasRecordSet;

	public function __construct(
		private readonly RecordNodeSet $nodeSet,
		private readonly string        $recordId
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