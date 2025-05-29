<?php

namespace netPhramework\db\nodes;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\core\RecordProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\locating\redirectors\RedirectToParent;

class Update extends RecordProcess
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