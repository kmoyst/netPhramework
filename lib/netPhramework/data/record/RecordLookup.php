<?php

namespace netPhramework\data\record;

use netPhramework\data\exceptions\FieldAbsent;
use netPhramework\data\exceptions\MappingException;
use netPhramework\data\exceptions\RecordNotFound;
use netPhramework\data\exceptions\ValueInaccessible;

class RecordLookup
{
	private string $id;

	public function __construct(private readonly RecordSet $recordSet) {}

	public function setId(string $id): self
	{
		$this->id = $id;
		return $this;
	}

    /**
     * @param string $fieldName
     * @return string|null
     * @throws ValueInaccessible
	 * @throws RecordNotFound
     */
	public function getValue(string $fieldName):?string
	{
		if(!isset($this->id))
            throw new ValueInaccessible("Value inaccessible. No id set.");
        try {
            return $this->recordSet->getRecord($this->id)->getValue($fieldName);
        } catch (FieldAbsent|MappingException $e) {
            throw new ValueInaccessible($e->getMessage());
        }
	}
}