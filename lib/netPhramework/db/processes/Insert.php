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
use netPhramework\exceptions\Exception;

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
     * @throws Exception
     * @throws FieldAbsent
     * @throws MappingException
     */
	public function handleExchange(
        Exchange $exchange, RecordSet $recordSet): void
	{
        ($this->saveProcess ??
            new Save($this->dispatcher ?? new DispatchToParent('')))
			    ->handleExchange($exchange, $recordSet->newRecord());
	}
}