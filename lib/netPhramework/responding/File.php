<?php

namespace netPhramework\responding;

readonly class File
{
	public function __construct(
		private string $fileName,
		private string $fileType,
		private string $storedPath) {}

	public function getFileName(): string
	{
		return $this->fileName;
	}

	public function getFileType(): string
	{
		return $this->fileType;
	}

	public function getStoredPath(): string
	{
		return $this->storedPath;
	}
}