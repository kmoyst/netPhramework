<?php

namespace netPhramework\routing;

use netPhramework\exceptions\PathException;

interface Reroutable
{
	/**
	 * @param Path $tail
	 * @return $this
	 * @throws PathException
	 */
	public function appendPath(Path $tail):self;
	/**
	 * @param string $name
	 * @return $this
	 * @throws PathException
	 */
	public function appendName(string $name):self;
	/**
	 * @param Route $route
	 * @return $this
	 * @throws PathException
	 */
	public function appendRoute(Route $route):self;
	public function pop():Reroutable;
	public function clear():Reroutable;
}