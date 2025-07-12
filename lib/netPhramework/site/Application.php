<?php

namespace netPhramework\site;

use netPhramework\exceptions\Exception;
use netPhramework\resources\Directory;

interface Application
{
	/**
	 * @param Directory $root
	 * @return void
	 * @throws Exception
	 */
    public function asPassive(Directory $root):void;

	/**
	 * @param Directory $root
	 * @return void
	 * @throws Exception
	 */
	public function asActive(Directory $root):void;
}