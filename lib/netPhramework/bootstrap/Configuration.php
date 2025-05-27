<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Directory;
use netPhramework\core\Page;
use netPhramework\rendering\WrapperConfiguration;

class Configuration
{
	/**
	 * @param Directory $root
	 * @return void
	 * @throws \Exception
	 */
    public function configurePassiveNode(Directory $root):void
	{
		$root->add(new Page(
			'getting-started','','Welcome to netPhramework'));
	}

	/**
	 * @param Directory $root
	 * @return void
	 * @throws \Exception
	 */
	public function configureActiveNode(Directory $root):void {}

	/**
	 * @param WrapperConfiguration $wrapper
	 * @return void
	 */
	public function configureWrapper(WrapperConfiguration $wrapper):void
	{
		$wrapper->addStyleSheet('framework-stylesheet');
	}
}