<?php

namespace netPhramework\data\resources;

use netPhramework\data\mapping\File;
use netPhramework\data\nodes\RecordProcess;
use netPhramework\exchange\Exchange;

class Download extends RecordProcess
{
	public function __construct	(
		private readonly File $file,
		)
	{
	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$this->file->setRecord($this->record);
		$exchange->transferFile($this->file);
	}
}