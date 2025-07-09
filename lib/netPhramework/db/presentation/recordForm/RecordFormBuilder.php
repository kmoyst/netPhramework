<?php

namespace netPhramework\db\presentation\recordForm;

use netPhramework\db\core\Record;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\presentation\InputSet;

class RecordFormBuilder
{
	private InputSet $inputs;
	private Record $record;

	public function __construct(private readonly RecordFormStrategy $strategy)
	{
		$this->inputs  = new InputSet();
	}

	public function setRecord(Record $record): RecordFormBuilder
	{
		$this->record = $record;
		return $this;
	}

	/**
	 * @return $this
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function addRecordInputs():RecordFormBuilder
	{
		$this->strategy->addInputs($this->record, $this->inputs);
		foreach($this->inputs as $input)
			$input->setValue($this->record->getValue($input->getName()));
		return $this;
	}

	public function getInputSet():InputSet
	{
		return $this->inputs;
	}
}