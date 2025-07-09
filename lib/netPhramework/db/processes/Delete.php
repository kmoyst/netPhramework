<?php

namespace netPhramework\db\processes;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\resources\RecordProcess;
use netPhramework\db\exceptions\MappingException;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\locating\redirectors\RedirectToParent;

class Delete extends RecordProcess
{
    protected Redirector $dispatcher;

	public function __construct(
		?Redirector $dispatcher = null,
		)
	{
        $this->dispatcher = $dispatcher ?? new RedirectToParent('');
	}

    /**
     * @param Exchange $exchange
     * @return void
     * @throws MappingException
     * @throws Exception
     */
	public function handleExchange(Exchange $exchange): void
	{
		$this->record->drop();
		$exchange->redirect($this->dispatcher);
	}
}