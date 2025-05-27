<?php

namespace netPhramework\dispatching;

use netPhramework\common\Variables;
use netPhramework\exceptions\InvalidUri;

/**
 * Adapts string Uri to MutablePath and Variables
 */
readonly class UriAdapter
{
	public function __construct(private string $uri) {}

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

	/**
	 * @return MutablePath
	 * @throws InvalidUri
	 */
	public function getPath():MutablePath
    {
        $path = new MutablePath();
        $pattern = '|^/([^?]*)|';
        if(!preg_match($pattern, $this->uri, $matches))
			throw new InvalidUri("Invalid Uri: $this->uri");
		$this->traverseArray($path, explode('/', $matches[1]));
        return $path;
    }

    private function traverseArray(MutablePath $path, array $names):void
    {
        $path->setName($names[0]);
        if(sizeof($names) > 1)
        {
            $path->append('');
            $this->traverseArray($path->getNext(), array_slice($names, 1));
        }
    }
}