<?php
namespace netPhramework\bootstrap;
readonly class Loader
{
	protected string $extension;
	protected array $sourceRoots;

	public function __construct()
	{
		$this->sourceRoots = [
			'../lib',
			__DIR__.'/../../'
		];
		$this->extension = 'php';
	}

	public function autoload(string $className):void
	{
		$filePath = str_replace('\\','/',$className);
		foreach ($this->sourceRoots as $sourceRoot) {
			$fullPath = "$sourceRoot/$filePath.$this->extension";
			if(!file_exists($fullPath)) continue;
			require_once $fullPath;
			return;
		}
	}
}
$loader = new Loader();
spl_autoload_register([$loader, 'autoload']);
