<?php

namespace netPhramework\db\resources;

use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\nodes\RecordProcess;
use netPhramework\exceptions\Exception;
use netPhramework\exchange\Exchange;
use netPhramework\routing\redirectors\Redirector;
use netPhramework\routing\redirectors\RedirectToParent;

class Update extends RecordProcess
{
	public function __construct(
		private readonly ?RecordProcess $saveProcess = null,
		private readonly ?Redirector    $dispatcher = null,
		)
	{
	}

    /**
     * @param Exchange $exchange
     * @return void
     * @throws FieldAbsent
     * @throws MappingException
     * @throws Exception
     */
	public function handleExchange(Exchange $exchange): void
	{
        ($this->saveProcess ??
			new Save($this->dispatcher ?? new RedirectToParent('')))
				->setRecord($this->record)
			    ->handleExchange($exchange);
    }
}