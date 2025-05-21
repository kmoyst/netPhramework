<?php

namespace netPhramework\db\presentation\recordForm;

use netPhramework\db\core\Record;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Cell;
use netPhramework\presentation\FormInput\InputSet;
use netPhramework\presentation\FormInput\InputSetBuilder;

readonly class RecordFormStrategyBasic implements RecordFormStrategy
{
	/**
	 * @param Record $record
	 * @param InputSet $inputSet
	 * @return void
	 * @throws MappingException
	 */
	public function addInputs(Record $record, InputSet $inputSet): void
	{
		foreach($record->getCellSet() as $cell)
			if($cell->getName() !== $record->getIdKey())
				$this->guessAndAdd($inputSet, $cell);
	}

	protected function guessAndAdd(InputSetBuilder $builder, Cell $cell):void
	{
		$name    = $cell->getName();
		$value   = $cell->getValue();

		if($name === 'password')
			$builder->passwordInput($name)->setValue('');
		else
			$builder->textInput($name)->setValue($value);
	}
}