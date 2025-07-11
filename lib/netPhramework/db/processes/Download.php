<?php

namespace netPhramework\db\processes;

use netPhramework\db\mapping\File;
use netPhramework\db\resources\RecordProcess;
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