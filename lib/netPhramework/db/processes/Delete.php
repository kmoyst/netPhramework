<?php

namespace netPhramework\db\processes;

use netPhramework\core\Exchange;
use netPhramework\db\core\Record;
use netPhramework\db\core\RecordProcess;
use netPhramework\db\exceptions\MappingException;
use netPhramework\dispatching\dispatchers\Dispatcher;
use netPhramework\dispatching\dispatchers\DispatchToParent;
use netPhramework\exceptions\Exception;

class Delete extends RecordProcess
{
    protected Dispatcher $dispatcher;

	public function __construct(?Dispatcher $dispatcher = null,
                                ?string $name = null)
	{
        $this->dispatcher = $this->dispatcher ?? new DispatchToParent('');
		parent::__construct($name);
	}

    /**
     * @param Exchange $exchange
     * @param Record $record
     * @return void
     * @throws MappingException
     * @throws Exception
     */
	public function handleExchange(Exchange $exchange, Record $record): void
	{
		$record->drop();
		$exchange->redirect($this->dispatcher);
	}
}