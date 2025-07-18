<?php

namespace netPhramework\core;

use netPhramework\exceptions\Exception;
use netPhramework\nodes\Directory;

interface Application
{
	/**
	 * @param Directory $root
	 * @return void
	 * @throws Exception
	 */
	public function configurePassiveNode(Directory $root):void;

	/**
	 * @param Directory $root
	 * @return void
	 * @throws Exception
	 */
	public function configureActiveNode(Directory $root):void;
}