<?php

namespace netPhramework\transferring;

interface File
{
	public function getFileName(): string;
	public function getFileType(): string;
	public function getStoredPath(): string;
}