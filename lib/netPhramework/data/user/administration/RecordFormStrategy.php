<?php

namespace netPhramework\data\user\administration;
use netPhramework\data\presentation\recordForm\RecordFormStrategy as baseStrategy;
use netPhramework\data\record\Record;
use netPhramework\presentation\InputSet;
use netPhramework\user\UserRole;

class RecordFormStrategy implements baseStrategy
{
	public function addInputs(Record $record, InputSet $inputSet): void
	{
		$inputSet->textInput('username');
		$inputSet->textInput('first-name');
		$inputSet->textInput('last-name');
		$inputSet->textInput('email-address');
		$inputSet->selectInputWithLabel('role', $this->getRoleOptions());
	}

	private function getRoleOptions():array
	{
		$options = UserRole::toArray();
		unset($options['1']);
		return $options;
	}
}