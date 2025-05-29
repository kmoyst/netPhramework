<?php

namespace netPhramework\core;

interface File
{
	public function getFileName(): string;
	public function getFileType(): string;
	public function getStoredPath(): string;
}