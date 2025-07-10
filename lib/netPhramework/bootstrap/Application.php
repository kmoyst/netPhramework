<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Directory;
use netPhramework\core\Exception;
use netPhramework\rendering\Wrapper;

abstract class Application
{
	/**
	 * @param Directory $root
	 * @return void
	 * @throws Exception
	 */
    abstract public function buildPassiveTree(Directory $root):void;

	/**
	 * @param Directory $root
	 * @return void
	 * @throws Exception
	 */
	abstract public function buildActiveTree(Directory $root):void;

	/**
	 * @param Wrapper $wrapper
	 * @return void
	 */
	public function configureWrapper(Wrapper $wrapper):void
	{
		$wrapper->addStyleSheet('framework-stylesheet');
	}
}