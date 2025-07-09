<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Directory;
use netPhramework\core\Page;
use netPhramework\rendering\Wrapper;

class Configuration
{
    public function buildPassiveTree(Directory $root):void
	{
		$root->add(new Page('getting-started','','Welcome to netPhramework'));
	}

	public function buildActiveTree(Directory $root):void {}

	public function configureWrapper(Wrapper $wrapper):void
	{
		$wrapper->addStyleSheet('framework-stylesheet');
	}
}