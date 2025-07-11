<?php

namespace netPhramework\core;

use netPhramework\exceptions\Exception;
use netPhramework\resources\Directory;

interface NodeBuilder
{

	/**
	 * @param Directory $root
	 * @return void
	 * @throws Exception
	 */
    public function buildPassiveNode(Directory $root):void;

	/**
	 * @param Directory $root
	 * @return void
	 * @throws Exception
	 */
	public function buildActiveNode(Directory $root):void;
}