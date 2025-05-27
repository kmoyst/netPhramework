<?php

namespace netPhramework\db\processes;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\core\RecordProcess;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\dispatching\redirectors\Redirector;
use netPhramework\dispatching\redirectors\RedirectToParent;

class Insert extends RecordSetProcess
{
	public function __construct(
		private readonly ?RecordProcess $saveProcess = null,
        private readonly ?Redirector    $dispatcher = null,
		?string                         $name = null)
	{
		$this->name = $name;
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