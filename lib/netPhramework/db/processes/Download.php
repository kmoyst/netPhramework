<?php

namespace netPhramework\db\processes;

use netPhramework\core\Exchange;
use netPhramework\db\resources\RecordProcess;
use netPhramework\db\mapping\File;

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