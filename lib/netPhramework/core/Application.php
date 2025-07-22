<?php

namespace netPhramework\core;

use netPhramework\nodes\Directory;

abstract class Application
{
	/**
	 * @param Directory $root
	 * @return void
	 */
	abstract public function configurePassiveNode(Directory $root):void;

	/**
	 * @param Directory $root
	 * @return void
	 */
	public function configureActiveNode(Directory $root):void {}
}