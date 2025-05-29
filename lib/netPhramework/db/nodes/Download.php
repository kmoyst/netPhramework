<?php

namespace netPhramework\db\nodes;

use netPhramework\core\Exchange;
use netPhramework\db\core\RecordProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\responding\File;

class Download extends RecordProcess
{
	public function __construct	(
		private readonly string $storedPathField,
		private readonly string $fileTypeField,
		private readonly string $fileNameField) {}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$storedPath = $this->record->getValue($this->storedPathField);
		$desc		= $this->record->getValue($this->fileNameField);
		$fileType	= $this->record->getValue($this->fileTypeField);
		$filePrefix = str_replace(' ','', $desc);
		preg_match('|\.([^.]+)$|', $storedPath, $m);
		$fileName = "$filePrefix.$m[1]";
		$file = new File($fileName, $fileType, $storedPath);
		$exchange->transferFile($file);
	}
}