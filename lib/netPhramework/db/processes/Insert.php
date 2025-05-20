<?php

namespace netPhramework\db\processes;

use netPhramework\core\Exchange;
use netPhramework\db\core\RecordProcess;
use netPhramework\db\core\RecordSet;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\dispatching\Dispatcher;
use netPhramework\dispatching\DispatchToParent;

class Insert extends RecordSetProcess
{
	public function __construct(
		private readonly ?RecordProcess $saveProcess = null,
        private readonly ?Dispatcher $dispatcher = null,
		?string $name = null)
	{
		parent::__construct($name);
	}

	/**
	 * @param Exchange $exchange
	 * @param RecordSet $recordSet
	 * @return void
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function execute(Exchange $exchange, RecordSet $recordSet): void
	{
		($this->saveProcess ?? (new Save(new DispatchToParent(''))))
			->execute($exchange, $recordSet->newRecord());
	}
}