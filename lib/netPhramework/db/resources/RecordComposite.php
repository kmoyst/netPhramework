<?php

namespace netPhramework\db\resources;

use netPhramework\core\CompositeResource;
use netPhramework\core\Resource;
use netPhramework\db\common\HasRecordSet;
use netPhramework\db\exceptions\MappingException;
use netPhramework\exceptions\ResourceNotFound;

class RecordComposite extends CompositeResource implements RecordSetChild
{
	use HasRecordSet;

	public function __construct(
		private readonly RecordChildSet $childSet,
		private readonly string $recordId
	) {}

	/**
	 * @param string $id
	 * @return Resource
	 * @throws MappingException
	 * @throws ResourceNotFound
	 */
	public function getChild(string $id): Resource
	{
		$record = $this->recordSet->getRecord($this->recordId);
		return $this->childSet->get($id)->setRecord($record);
	}

	public function getName(): string
	{
		return $this->recordId;
	}
}