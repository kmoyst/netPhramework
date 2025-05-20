<?php

namespace netPhramework\dispatching;

use netPhramework\common\Variables;
use netPhramework\exceptions\Exception;

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
	 * @return Path
	 * @throws Exception
	 */
	public function getPath():Path
    {
        $path = new Path();
        $pattern = '|^/([^?]*)|';
        if(!preg_match($pattern, $this->uri, $matches))
			throw new Exception("Invalid Uri: $this->uri");
		$this->traverseArray($path, explode('/', $matches[1]));
        return $path;
    }

    private function traverseArray(Path $path, array $names):void
    {
        $path->setName($names[0]);
        if(sizeof($names) > 1)
        {
            $path->append('');
            $this->traverseArray($path->getNext(), array_slice($names, 1));
        }
    }
}