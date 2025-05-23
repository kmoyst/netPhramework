<?php

namespace netPhramework\db\processes;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\core\Record;
use netPhramework\db\core\RecordProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\db\exceptions\MappingException;
use netPhramework\dispatching\dispatchers\Dispatcher;
use netPhramework\dispatching\dispatchers\DispatchToParent;

class Save extends RecordProcess
{
    protected Dispatcher $onSuccess;

	public function __construct(
        ?Dispatcher $onSuccess = null,
        ?string $name = null)
    {
        $this->onSuccess = $onSuccess ?? new DispatchToParent('');
        parent::__construct($name);
    }

    /**
     * @param Exchange $exchange
     * @param Record $record
     * @return void
     * @throws FieldAbsent
     * @throws MappingException
     * @throws Exception
     */
	public function handleExchange(Exchange $exchange, Record $record):void
	{
		try {
			foreach($exchange->getParameters() as $k => $v)
				if($record->getCellSet()->has($k))
					$record->getCell($k)->setValue($v);
			$record->save();
			$exchange->redirect($this->onSuccess);
		} catch (InvalidValue $e) {
            $exchange->error($e, new DispatchToParent());
		}
	}
}