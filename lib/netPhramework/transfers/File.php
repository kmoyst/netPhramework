<?php

namespace netPhramework\transfers;

use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Record;

class File
{
	private string $fileType;
	private string $fileName;
	private string $storedPath;

	public function __construct(
		private readonly string $fileInfoField,
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
		$fileInfo = $record->getValue($this->fileInfoField);
		$filePrefix = $record->getValue($this->fileNameField);
		$filePrefix = str_replace(' ', '', $filePrefix);
		$bits  = explode(' ', $fileInfo);
		$this->fileType   = $bits[1];
		$this->storedPath 	  = $bits[0];
		preg_match('|\.([^.]+)$|', $this->storedPath,$m);
		$ext = $m[1];
		$this->fileName = "$filePrefix.$ext";
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