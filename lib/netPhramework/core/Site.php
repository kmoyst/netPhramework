<?php

namespace netPhramework\core;

use netPhramework\bootstrap\Application;

class Site
{
	public Application $application;

	/**
	 * @return Socket
	 * @throws Exception
	 */
	public function openPassiveSocket(): Socket
	{
		$root = new Directory('');
		$this->application->buildPassiveTree($root);
		return new Socket($root);
	}

	/**
	 * @return Socket
	 * @throws Exception
	 */
	public function openActiveSocket(): Socket
	{
		$root = new Directory('');
		$this->application->buildActiveTree($root);
		return new Socket($root);
	}
}