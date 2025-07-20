<?php

namespace netPhramework\data\core;

use netPhramework\data\exceptions\FieldAbsent;
use netPhramework\data\exceptions\MappingException;
use netPhramework\data\exceptions\RecordNotFound;
use netPhramework\data\exceptions\RecordRetrievalException;

readonly class RecordFinder
{
	public function __construct(private RecordSet $recordSet) {}

	/**
	 * @param string $fieldName
	 * @param string $value
	 * @return Record
	 * @throws FieldAbsent
	 * @throws RecordNotFound
	 * @throws RecordRetrievalException
	 * @throws MappingException
	 */
	public function findUniqueRecord(string $fieldName, string $value):Record
	{
		$field = $this->recordSet->getFieldSet()->getField($fieldName);
		if(!$field->mustBeUnique())
			throw new MappingException("Not unique field: $fieldName");
		foreach($this->recordSet as $record)
			if($record->getValue($fieldName) === $value) return $record;
		throw new RecordNotFound("Record not found with $fieldName = $value");
	}
}