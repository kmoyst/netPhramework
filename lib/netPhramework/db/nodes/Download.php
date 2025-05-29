<?php

namespace netPhramework\db\nodes;

use netPhramework\core\Exchange;
use netPhramework\db\core\RecordProcess;
use netPhramework\db\presentation\helpers\File;

class Download extends RecordProcess
{
	public function __construct	(private readonly File $file) {}

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