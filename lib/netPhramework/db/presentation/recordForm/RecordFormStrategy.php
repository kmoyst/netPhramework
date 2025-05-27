<?php

namespace netPhramework\db\presentation\recordForm;

use netPhramework\db\mapping\Record;
use netPhramework\presentation\FormInput\InputSet;

interface RecordFormStrategy
{
	public function addInputs(Record $record, InputSet $inputSet):void;
}