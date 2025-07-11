<?php

namespace netPhramework\db\processes;

use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\resources\RecordProcess;
use netPhramework\db\resources\RecordSetProcess;
use netPhramework\exceptions\Exception;
use netPhramework\exchange\Exchange;
use netPhramework\routing\redirectors\Redirector;
use netPhramework\routing\redirectors\RedirectToParent;

class Insert extends RecordSetProcess
{
	public function __construct(
		private readonly ?RecordProcess $saveProcess = null,
        private readonly ?Redirector    $dispatcher = null)
	{
	}

    /**
     * @param Exchange $exchange
     * @return void
     * @throws Exception
     * @throws FieldAbsent
     * @throws MappingException
     */
	public function handleExchange(Exchange $exchange): void
	{
        ($this->saveProcess ??
            new Save($this->dispatcher ?? new RedirectToParent('')))
				->setRecord($this->recordSet->newRecord())
			    ->handleExchange($exchange);
	}
}