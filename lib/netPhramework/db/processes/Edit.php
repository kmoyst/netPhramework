<?php

namespace netPhramework\db\processes;

use netPhramework\core\Exchange;
use netPhramework\db\core\Record;
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
		parent::__construct($name);
	}

	public function execute(Exchange $exchange, Record $record): void
	{
		$strategy = $this->formStrategy ?? new RecordFormStrategyBasic();
		$inputSet = new RecordFormBuilder($strategy)
			->setRecord($record)
			->addRecordInputs()
			->setCallbackKey($exchange->getCallbackKey())
			->addCallback($exchange->stickyCallback())
			->getInputSet()
		;
		$view = new View('edit');
		$view->getVariables()->add('inputs', $inputSet);
		$view->getVariables()->add('action', 'update');
		$exchange->ok($view->setTitle("Edit Record"));
	}
}