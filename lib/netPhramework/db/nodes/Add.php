<?php

namespace netPhramework\db\nodes;

use netPhramework\core\Exchange;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Record;
use netPhramework\db\presentation\recordForm\RecordFormBuilder;
use netPhramework\db\presentation\recordForm\RecordFormStrategy;
use netPhramework\db\presentation\recordForm\RecordFormStrategyBasic;
use netPhramework\presentation\CallbackInput;
use netPhramework\rendering\View;

class Add extends RecordSetProcess
{
	public function __construct(
		protected readonly ?RecordFormStrategy $formStrategy = null,
		string $name = 'add')
	{
		$this->name = $name;
	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function handleExchange(Exchange $exchange): void
    {
		$editForm = $this->createEditForm(
			new CallbackInput($exchange),
			$this->recordSet->newRecord(),
			'insert', $exchange->callbackLink())
		;
		$view = new View('edit')
			->setTitle("Add Record")
			->add('editForm', $editForm)
		;
		$exchange->ok($view);
	}

	/**
	 * @param CallbackInput $callbackInput
	 * @param Record $record
	 * @param string $action
	 * @param string $callbackLink
	 * @return View
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	private function createEditForm(
		CallbackInput $callbackInput, Record $record,
		string $action, string $callbackLink):View
	{
		$strategy = $this->formStrategy ?? new RecordFormStrategyBasic();
		$inputSet = new RecordFormBuilder($strategy)
			->setRecord($record)
			->addRecordInputs()
			->getInputSet()
		;
		return new View('edit-form')
			->add('hasFileInput', $inputSet->hasFileInput())
			->add('inputs', $inputSet)
			->add('callbackInput', $callbackInput)
			->add('action', $action)
			->add('callbackLink', $callbackLink)
			;
	}
}