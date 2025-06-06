<?php

namespace netPhramework\locating;

use netPhramework\common\Variables;
use netPhramework\exceptions\InvalidUri;

readonly class UriAdapter
{
	public function __construct(private string $uri) {}

	/**
	 * @return MutablePath
	 * @throws InvalidUri
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

    private function traverseArray(MutablePath $path, array $names):void
    {
		if(count($names) === 0) return;
		$path->append(array_shift($names));
		$this->traverseArray($path->getNext(), $names);
    }

	public function getParameters():Variables
	{
		$vars = new Variables();
		$pattern = '|\?(.+)$|';
		if(preg_match($pattern, $this->uri, $matches))
		{
			parse_str($matches[1], $arr);
			$vars->merge($arr);
		}
		return $vars;
	}
}