<?php

namespace netPhramework\data\resources;

use netPhramework\data\mapping\File;
use netPhramework\data\asset\AssetChildProcess;
use netPhramework\exchange\Exchange;

class Download extends AssetChildProcess
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