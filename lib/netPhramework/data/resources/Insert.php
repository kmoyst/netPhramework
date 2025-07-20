<?php

namespace netPhramework\data\resources;

use netPhramework\data\exceptions\FieldAbsent;
use netPhramework\data\exceptions\MappingException;
use netPhramework\data\asset\AssetProcess;
use netPhramework\data\asset\AssetRecordProcess;
use netPhramework\exceptions\Exception;
use netPhramework\exchange\Exchange;
use netPhramework\routing\redirectors\Redirector;
use netPhramework\routing\redirectors\RedirectToParent;

class Insert extends AssetProcess
{
	public function __construct(
		private readonly ?AssetRecordProcess $saveProcess = null,
        private readonly ?Redirector         $dispatcher = null)
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