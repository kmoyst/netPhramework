<?php

namespace netPhramework\core;

use netPhramework\exceptions\Exception;
use netPhramework\nodes\Directory;

abstract class Application
{
	/**
	 * @param Directory $root
	 * @return void
	 * @throws Exception
	 */
	abstract public function configurePassiveNode(Directory $root):void;

	/**
	 * @param Directory $root
	 * @return void
	 * @throws Exception
	 */
	abstract public function configureActiveNode(Directory $root):void;
}