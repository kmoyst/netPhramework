<?php

namespace netPhramework\db\nodes;

use netPhramework\common\Variables;
use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\core\RecordProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Record;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\locating\redirectors\RedirectToParent;

class Save extends RecordProcess
{
    protected Redirector $onSuccess;

	public function __construct(
        ?Redirector $onSuccess = null,
        ?string     $name = null)
    {
        $this->onSuccess = $onSuccess ?? new RedirectToParent('');
        $this->name = $name;
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
		$parameters = $exchange->getParameters();
		$this->manageUpload($exchange, $parameters, $record);
		try {
			foreach($parameters as $k => $v)
				if($record->getCellSet()->has($k))
				{
					$record->getCell($k)->setValue($v);
				}
			$record->save();
			$exchange->redirect($this->onSuccess);
		} catch (InvalidValue $e) {
            $exchange->error($e, new RedirectToParent());
		}
	}

	/**
	 * @param Exchange $exchange
	 * @param Variables $parameters
	 * @param Record $record
	 * @return void
	 * @throws FieldAbsent
	 * @throws InvalidValue
	 * @throws MappingException
	 */
	private function manageUpload(
		Exchange $exchange, Variables $parameters, Record $record):void
	{
		$um = $exchange->getUploadManager();
		if($um->hasFile() && $record->getCellSet()->has($um->getFieldName()))
		{
			$storedFilePath = $um->saveFile();
			$type = $um->getType();
			$cellValue = "$storedFilePath $type";
			$record->getCellSet()->getCell($um->getFieldName())
				->setValue($cellValue);
			$parameters->remove($um->getFieldName());
		}
	}
}