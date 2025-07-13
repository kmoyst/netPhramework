<?php

namespace netPhramework\db\core;

use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\ValueInaccessible;

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