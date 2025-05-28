<?php

namespace netPhramework\db\nodes;

use netPhramework\core\Exchange;
use netPhramework\db\core\RecordProcess;
use netPhramework\db\presentation\recordForm\RecordFormBuilder;
use netPhramework\db\presentation\recordForm\RecordFormStrategy;
use netPhramework\db\presentation\recordForm\RecordFormStrategyBasic;
use netPhramework\rendering\View;

class Edit extends RecordProcess
{
	public function __construct(
		private readonly ?RecordFormStrategy $formStrategy = null,
		?string $name = null)
	{
		$this->name = $name;
	}

	public function handleExchange(Exchange $exchange): void
	{
		$view = new View('edit')
			->setTitle("Edit Record")
			->add('editForm', $this->createEditForm($exchange))
		;
		$exchange->ok($view);
	}

	private function createEditForm(Exchange $exchange):View
	{
		$strategy = $this->formStrategy ?? new RecordFormStrategyBasic();
		$inputSet = new RecordFormBuilder($strategy)
			->setRecord($this->record)
			->addRecordInputs()
			->getInputSet()->addCustom($exchange->callbackFormInput())
		;
		return new View('edit-form')
			->add('inputs', $inputSet)
			->add('action', 'update')
			->add('callbackLink', $exchange->callbackLink())
			;
	}
}