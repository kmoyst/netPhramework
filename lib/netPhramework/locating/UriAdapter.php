<?php

namespace netPhramework\locating;

use netPhramework\common\Variables;
use netPhramework\exceptions\InvalidUri;
use netPhramework\exceptions\PathException;

readonly class UriAdapter
{
	public function __construct(private string $uri) {}

	/**
	 * @return MutablePath
	 * @throws InvalidUri
	 * @throws PathException
	 */
	public function getPath():MutablePath
    {
        if(!preg_match('|^/([^?]*)|', $this->uri, $matches))
			throw new InvalidUri("Invalid Uri: $this->uri");
		$names = explode('/', $matches[1]);
		$path  = new MutablePath(array_shift($names));
		$this->traverseArray($path, $names);
        return $path;
    }

	/**
	 * @param MutablePath $path
	 * @param array $names
	 * @return void
	 * @throws PathException
	 */
    private function traverseArray(MutablePath $path, array $names):void
    {
		if(count($names) === 0) return;
		$path->setNext(array_shift($names));
		$this->traverseArray($path->getNext(), $names);
    }

	public function getParameters():Variables
	{
		$vars = new Variables();
		if(preg_match('|\?(.+)$|', $this->uri, $matches))
		{
			parse_str($matches[1], $arr);
			$vars->merge($arr);
		}
		return $vars;
	}
}