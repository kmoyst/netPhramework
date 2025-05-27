<?php

namespace netPhramework\db\presentation\recordForm;

use netPhramework\db\mapping\Record;
use netPhramework\presentation\FormInput\InputSet;

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

	public function addRecordInputs():RecordFormBuilder
	{
		$this->strategy->addInputs($this->record, $this->inputs);
		return $this;
	}

	public function getInputSet():InputSet
	{
		return $this->inputs;
	}
}