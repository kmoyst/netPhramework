<?php

namespace netPhramework\data\resources;

use netPhramework\data\exceptions\FieldAbsent;
use netPhramework\data\exceptions\MappingException;
use netPhramework\data\asset\AssetChildProcess;
use netPhramework\data\presentation\recordForm\RecordFormBuilder;
use netPhramework\data\presentation\recordForm\RecordFormStrategy;
use netPhramework\data\presentation\recordForm\RecordFormStrategyBasic;
use netPhramework\exchange\Exchange;
use netPhramework\presentation\CallbackInput;
use netPhramework\rendering\View;

class Edit extends AssetChildProcess
{
	public function __construct(
		private readonly ?RecordFormStrategy $formStrategy = null
		)
	{
	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$view = new View('edit')
			->setTitle("Edit Record")
			->add('editForm', $this->createEditForm($exchange))
		;
		$exchange->ok($view);
	}

	/**
	 * @param Exchange $exchange
	 * @return View
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	private function createEditForm(Exchange $exchange):View
	{
		$strategy = $this->formStrategy ?? new RecordFormStrategyBasic();
		$inputSet = new RecordFormBuilder($strategy)
			->setRecord($this->record)
			->addRecordInputs()
			->getInputSet()
		;
		return new View('edit-form')
			->add('hasFileInput', $inputSet->hasFileInput())
			->add('inputs', $inputSet)
			->add('callbackInput', new CallbackInput($exchange))
			->add('action', 'update')
			->add('callbackLink', $exchange->callbackLink())
			;
	}
}