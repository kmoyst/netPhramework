<?php

namespace netPhramework\http;

use netPhramework\exceptions\InvalidUri;
use netPhramework\routing\Path;
use netPhramework\routing\PathFromArray;

class PathFromUri extends Path
{
	public ?string $name = null {get{
		$this->parse();
		return $this->path->getName();
	}set(?string $name){
		$this->path->setName($name);
	}}

	public ?Path $next = null {get{
		$this->parse();
		return $this->path->getNext();
	}set(?Path $next){
		$this->path->setNext($next);
	}}


	private ?Path $path = null;

	public function __construct(private readonly string $uri)
	{
		parent::__construct();
	}

	/**
	 * @throws InvalidUri
	 */
	private function parse():void
	{
		if(isset($this->path)) return;
		if(!preg_match('|^/([^?]*)|', $this->uri, $matches))
			throw new InvalidUri("Invalid Uri: $this->uri");
		$names = explode('/', $matches[1]);
		$this->path = new PathFromArray($names);
	}
}