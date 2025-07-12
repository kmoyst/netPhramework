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
    public function asAPassiveResource(Directory $root):void;

	/**
	 * @param Directory $root
	 * @return void
	 * @throws Exception
	 */
	public function asAnActiveResource(Directory $root):void;
}