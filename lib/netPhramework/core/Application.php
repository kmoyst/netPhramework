<?php

namespace netPhramework\core;

use netPhramework\exceptions\Exception;
use netPhramework\resources\Directory;
use netPhramework\responding\Responder;

abstract class Application
{
	/**
	 * @param Responder $responder
	 * @return void
	 */
	public function configureResponder(Responder $responder):void
	{
		$responder->wrapper->addStyleSheet('framework-stylesheet');
		$responder->templateFinder
			->directory('../html')
			->directory(__DIR__ . '/../../../html')
			->extension('phtml')
			->extension('css')
		;
	}
	/**
	 * @param Directory $root
	 * @return void
	 * @throws Exception
	 */
    abstract public function buildPassiveTree(Directory $root):void;

	/**
	 * @param Directory $root
	 * @return void
	 * @throws Exception
	 */
	abstract public function buildActiveTree(Directory $root):void;
}