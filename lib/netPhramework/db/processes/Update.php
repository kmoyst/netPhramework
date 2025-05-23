<?php

namespace netPhramework\db\processes;

use netPhramework\core\Exchange;
use netPhramework\db\core\Record;
use netPhramework\db\core\RecordProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\dispatching\dispatchers\Dispatcher;
use netPhramework\dispatching\dispatchers\DispatchToParent;
use netPhramework\exceptions\Exception;

class Update extends RecordProcess
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
     * @param Record $record
     * @return void
     * @throws FieldAbsent
     * @throws MappingException
     * @throws Exception
     */
	public function handleExchange(Exchange $exchange, Record $record): void
	{
        ($this->saveProcess ??
			new Save($this->dispatcher ?? new DispatchToParent('')))
			    ->handleExchange($exchange, $record);
    }
}