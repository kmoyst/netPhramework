<?php

namespace netPhramework\db\processes;

use netPhramework\core\Exchange;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\presentation\recordForm\RecordFormBuilder;
use netPhramework\db\presentation\recordForm\RecordFormStrategy;
use netPhramework\db\presentation\recordForm\RecordFormStrategyBasic;
use netPhramework\rendering\View;

class Add extends RecordSetProcess
{
	public function __construct(
		protected readonly ?RecordFormStrategy $formStrategy = null,
		?string $name = null)
	{
		parent::__construct($name);
	}

	public function handleExchange(Exchange $exchange): void
    {

		$strategy = $this->formStrategy ?? new RecordFormStrategyBasic();
		$inputSet = new RecordFormBuilder($strategy)
			->setRecord($this->recordSet->newRecord())
			->addRecordInputs()
			->getInputSet()->addCustom($exchange->callbackFormInput())
		;
		$view = new View('edit');
		$view->getVariables()->add('inputs', $inputSet);
		$view->getVariables()->add('action', 'insert');
		$exchange->ok($view);
	}
}