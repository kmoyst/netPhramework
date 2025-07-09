<?php

namespace netPhramework\db\application\mapping;

use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\db\mapping\Record;
use netPhramework\db\mapping\RecordSet;

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