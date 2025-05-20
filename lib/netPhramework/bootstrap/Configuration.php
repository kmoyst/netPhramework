<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Directory;
use netPhramework\presentation\Page;
use netPhramework\rendering\WrapperSetup;

class Configuration
{
	/**
	 * @param Directory $directory
	 * @return void
	 * @throws \Exception
	 */
    public function configurePassiveNode(Directory $directory):void
	{
		$directory->leaf(new Page(
			'getting-started','','Welcome to netPhramework'));
	}

	/**
	 * @param Directory $directory
	 * @return void
	 * @throws \Exception
	 */
	public function configureActiveNode(Directory $directory):void {}

	/**
	 * @param WrapperSetup $wrapper
	 * @return void
	 */
	public function configureWrapper(WrapperSetup $wrapper):void {}
}