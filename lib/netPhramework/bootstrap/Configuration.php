<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Directory;
use netPhramework\core\Page;
use netPhramework\rendering\WrapperConfiguration;

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
	 * @param WrapperConfiguration $wrapper
	 * @return void
	 */
	public function configureWrapper(WrapperConfiguration $wrapper):void
	{
		$wrapper->addStyleSheet('framework-stylesheet');
	}
}