<?php

namespace netPhramework\data\resources;

use netPhramework\data\exceptions\FieldAbsent;
use netPhramework\data\exceptions\InvalidValue;
use netPhramework\data\exceptions\MappingException;
use netPhramework\data\asset\AssetRecordProcess;
use netPhramework\exceptions\Exception;
use netPhramework\exchange\Exchange;
use netPhramework\routing\redirectors\Redirector;
use netPhramework\routing\redirectors\RedirectToParent;
use netPhramework\routing\redirectors\RedirectToSibling;

class Save extends AssetRecordProcess
{
    protected Redirector $onSuccess;
	protected Redirector $onFailure;

	public function __construct(
        ?Redirector $onSuccess = null,
		?Redirector $onFailure = null
	)
    {
        $this->onSuccess = $onSuccess ?? new RedirectToParent('');
		$this->onFailure = $onFailure ?? new RedirectToSibling('');
    }

    /**
     * @param Exchange $exchange
     * @return void
     * @throws FieldAbsent
     * @throws MappingException
     * @throws Exception
     */
	public function handleExchange(Exchange $exchange):void
	{
		$record = $this->record;
		try {
			foreach($exchange->parameters as $k => $v)
				if($this->record->getCellSet()->has($k))
				{
					$this->record->setValue($k, $v);
				}
			$record->save();
			$exchange->redirect($this->onSuccess);
		} catch (InvalidValue $e) {
            $exchange->error($e, $this->onFailure);
		}
	}
}