<?php

namespace netPhramework\db\resources;

use netPhramework\db\mapping\File;
use netPhramework\db\nodes\AssetRecordProcess;
use netPhramework\exchange\Exchange;

class Download extends AssetRecordProcess
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