<?php

namespace netPhramework\db\presentation\recordForm;

use netPhramework\db\core\Record;
use netPhramework\dispatching\Location;
use netPhramework\presentation\FormInput\InputSet;

class RecordFormBuilder
{
	private InputSet $inputs;
	private Record $record;
	private string $callbackKey;

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

	public function setCallbackKey(string $callbackKey): RecordFormBuilder
	{
		$this->callbackKey = $callbackKey;
		return $this;
	}

	public function addCallback(string|Location $value):RecordFormBuilder
	{
		$this->inputs->hiddenInput($this->callbackKey)->setValue($value);
		return $this;
	}

	public function getInputSet():InputSet
	{
		return $this->inputs;
	}
}