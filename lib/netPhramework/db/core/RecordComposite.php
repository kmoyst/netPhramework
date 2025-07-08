<?php

namespace netPhramework\db\core;

use netPhramework\core\Composite;
use netPhramework\core\Node;
use netPhramework\db\exceptions\MappingException;
use netPhramework\exceptions\NodeNotFound;

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

	public function enlist(AssetNodeRecruiter $recruiter): void
	{
		$recruiter->recordSetChildSet->add($this);
	}
}