<?php

namespace netPhramework\db\resources;

use netPhramework\common\StringPredicate as Predicate;
use netPhramework\core\CompositeResource;
use netPhramework\core\Resource;
use netPhramework\db\core\RecordSet;
use netPhramework\db\resources\RecordSetChildSet as setChildSet;
use netPhramework\exceptions\ResourceNotFound;

class RecordResource extends CompositeResource
{
	public function __construct
	(
	public readonly string $name,
	public readonly RecordSet $recordSet,
	public readonly Predicate $recordIdPredicate   = new NumericIdPredicate(),
	public readonly RecordChildSet $recordChildSet = new RecordChildSet(),
	public readonly setChildSet $recordSetChildSet = new RecordSetChildSet()
	) {}

	public function getName(): string
	{
		return $this->name;
	}

	public function getChild(string $id): Resource
	{
		return $this->resolveChild($id)->setRecordSet($this->recordSet);
	}

	/**
	 * @param string $id
	 * @return RecordSetChild
	 * @throws ResourceNotFound
	 */
	private function resolveChild(string $id): RecordSetChild
	{
		if($this->recordIdPredicate->test($id))
			return new RecordComposite($this->recordChildSet, $id);
		else
			return $this->recordSetChildSet->get($id);
	}
}