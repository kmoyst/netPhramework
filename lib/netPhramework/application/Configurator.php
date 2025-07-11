<?php

namespace netPhramework\application;

use netPhramework\exceptions\Exception;
use netPhramework\resources\Directory;
use netPhramework\responding\Responder;

abstract class Configurator
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
    abstract public function configurePassiveNode(Directory $root):void;

	/**
	 * @param Directory $root
	 * @return void
	 * @throws Exception
	 */
	abstract public function configureActiveNode(Directory $root):void;
}