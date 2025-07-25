<?php

namespace netPhramework\data\resources;

use netPhramework\data\exceptions\FieldAbsent;
use netPhramework\data\exceptions\MappingException;
use netPhramework\data\nodes\RecordProcess;
use netPhramework\data\nodes\RecordSetProcess;
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