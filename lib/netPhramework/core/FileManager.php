<?php

namespace netPhramework\core;

class FileManager
{
	private array $uploadInfo;
	private string $fieldName;

	public function __construct(?array $uploadInfo = null)
	{
		$this->uploadInfo = $uploadInfo ?? $_FILES;
	}

	public function hasFile():bool
	{
		return !empty($this->uploadInfo);
	}

	public function getFileName():string
	{
		return $this->uploadInfo[$this->fieldName]['name'];
	}

	public function getFullPath():string
	{
		return $this->uploadInfo[$this->fieldName]['full_path'];
	}

	public function getType():string
	{
		return $this->uploadInfo[$this->fieldName]['type'];
	}

	public function getTempPath():string
	{
		return $this->uploadInfo[$this->fieldName]['tmp_name'];
	}

	public function getError():int
	{
		return $this->uploadInfo[$this->fieldName]['error'];
	}

	public function getSize():int
	{
		return $this->uploadInfo[$this->fieldName]['size'];
	}

	public function getFilePrefix():string
	{
		preg_match('|^(.+)\.[^.]+$|', $this->getFileName(), $m);
		return $m[1];
	}

	public function getFileExtension():string
	{
		preg_match('|^.+\.([^.]+)$|', $this->getFileName(), $m);
		return $m[1];
	}

	public function saveFile():string
	{
		$this->fieldName = array_keys($this->uploadInfo)[0];
		preg_match('|^(.+)\.([^.]+)$|', $this->getFileName(), $m);
		$newFileName = sha1($m[1]).uniqid().'.'.$m[2];
		$newFilePath = "../../uploads/$newFileName";
		move_uploaded_file($this->getTempPath(), $newFilePath);
		return $newFilePath;
	}
}