<?php

namespace netPhramework\data\presentation\recordForm;

use netPhramework\data\core\Record;
use netPhramework\presentation\InputSet;

interface RecordFormStrategy
{
	public function addInputs(Record $record, InputSet $inputSet):void;
}