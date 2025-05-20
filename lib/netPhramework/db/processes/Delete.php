<?php

namespace netPhramework\db\processes;

use netPhramework\core\Exchange;
use netPhramework\db\core\Record;
use netPhramework\db\core\RecordProcess;
use netPhramework\db\exceptions\MappingException;
use netPhramework\dispatching\Dispatcher;
use netPhramework\dispatching\DispatchToParent;

class Delete extends RecordProcess
{
	public function __construct(
        private readonly ?Dispatcher $dispatcher = null,
		?string $name = null)
	{
		parent::__construct($name);
	}

	/**
	 * @param Exchange $exchange
	 * @param Record $record
	 * @return void
	 * @throws MappingException
	 */
	public function execute(Exchange $exchange, Record $record): void
	{
		$record->drop();
		$exchange->callback($this->dispatcher ?? new DispatchToParent(''));
	}
}