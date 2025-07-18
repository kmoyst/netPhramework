<?php

namespace netPhramework\db\presentation\recordForm;

use netPhramework\db\core\Record;
use netPhramework\presentation\InputSet;

interface RecordFormStrategy
{
	public function addInputs(Record $record, InputSet $inputSet):void;
}