<?php

namespace netPhramework\db\transferring;

use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Record;

class File
{
	private string $fileType;
	private string $fileName;
	private string $storedPath;

	public function __construct(
		private readonly string $storedPathField,
		private readonly string $fileTypeField,
		private readonly string $fileNameField)
	{

	}

	/**
	 * @param Record $record
	 * @return $this
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function createFile(Record $record):self
	{
		$storedPath = $record->getValue($this->storedPathField);
		$desc		= $record->getValue($this->fileNameField);
		$fileType	= $record->getValue($this->fileTypeField);
		$this->storedPath = $storedPath;
		$this->fileType = $fileType;
		$filePrefix = str_replace(' ','', $desc);
		preg_match('|\.([^.]+)$|', $storedPath, $m);
		$this->fileName = "$filePrefix.$m[1]";
		return $this;
	}

	public function getFileType(): string
	{
		return $this->fileType;
	}

	public function getFileName(): string
	{
		return $this->fileName;
	}

	public function getStoredPath(): string
	{
		return $this->storedPath;
	}
}