<?php

namespace netPhramework\db\presentation\recordForm;

use netPhramework\db\core\Record;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Cell;
use netPhramework\db\mapping\FieldType;
use netPhramework\presentation\InputSet;
use netPhramework\presentation\InputSetBuilder;

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
		$name = $cell->getName();

		if($cell->getField()->getType() === FieldType::PARAGRAPH)
			$builder->textareaInput($name);
		else
			$builder->textInput($name);
	}
}