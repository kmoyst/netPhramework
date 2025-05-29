<?php

namespace netPhramework\db\transferring;

use netPhramework\core\Exchange;
use netPhramework\db\core\RecordProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;

class Download extends RecordProcess
{
	private File $file;

	/**
	 * @param File $file
	 */
	public function __construct(File $file)
	{
		$this->file = $file;
	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$this->file->createFile($this->record);
		$exchange->transferFile($this->file);
	}
}