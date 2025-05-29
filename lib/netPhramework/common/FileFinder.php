<?php

namespace netPhramework\common;

use netPhramework\exceptions\FileNotFound;

class FileFinder
{
	private array $directories = [];
	private array $extensions = [];

	public function directory(string $directory):FileFinder
	{
		$this->directories[] = $directory;
		return $this;
	}

	public function extension(string $extension):FileFinder
	{
		$this->extensions[] = $extension;
		return $this;
	}

	/**
	 * @param string $filename
	 * @return string
	 * @throws FileNotFound
	 */
	public function findPath(string $filename): string
	{
		foreach($this->directories as $d)
			foreach ($this->extensions as $e)
			{
				$path = "$d/$filename.$e";
				if(file_exists($path)) return $path;
			}
		throw new FileNotFound("FileMapper Not Found: $filename");
	}
}