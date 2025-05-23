<?php

namespace netPhramework\db\processes;

use netPhramework\core\Exchange;
use netPhramework\db\core\Record;
use netPhramework\db\core\RecordProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\db\exceptions\MappingException;
use netPhramework\dispatching\Dispatcher;
use netPhramework\dispatching\DispatchToParent;
use netPhramework\exceptions\Exception;

class Save extends RecordProcess
{
    protected Dispatcher $dispatcher;

	public function __construct(
        ?Dispatcher $dispatcher = null,
        ?string $name = null)
    {
        $this->dispatcher = $dispatcher ?? new DispatchToParent('');
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
			$exchange->redirect($this->dispatcher);
		} catch (InvalidValue $e) {
            $exchange->error($e, $this->dispatcher);
		}
	}
}