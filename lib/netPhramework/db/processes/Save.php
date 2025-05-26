<?php

namespace netPhramework\db\processes;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\core\Record;
use netPhramework\db\core\RecordProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\db\exceptions\MappingException;
use netPhramework\dispatching\redirectors\Redirector;
use netPhramework\dispatching\redirectors\RedirectToParent;

class Save extends RecordProcess
{
    protected Redirector $onSuccess;

	public function __construct(
        ?Redirector $onSuccess = null,
        ?string     $name = null)
    {
        $this->onSuccess = $onSuccess ?? new RedirectToParent('');
        parent::__construct($name);
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
			foreach($exchange->getParameters() as $k => $v)
				if($record->getCellSet()->has($k))
					$record->getCell($k)->setValue($v);
			$record->save();
			$exchange->redirect($this->onSuccess);
		} catch (InvalidValue $e) {
            $exchange->error($e, new RedirectToParent());
		}
	}
}