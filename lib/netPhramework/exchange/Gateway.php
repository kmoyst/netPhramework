<?php

namespace netPhramework\exchange;

use netPhramework\core\Application;
use netPhramework\exceptions\Exception;
use netPhramework\nodes\Directory;

class Gateway
{
	private Directory $root;

	public function __construct
	(
		private readonly Application $application
	)
	{
		$this->root = new Directory('root');
	}

	/**
	 * @param bool $requestingModification
	 * @return Router
	 * @throws Exception
	 */
	public function mapToRouter(bool $requestingModification):Router
	{
		if(!$requestingModification)
			$this->application->configurePassiveNode($this->root);
		else
			$this->application->configureActiveNode($this->root)
		;
		return new Router($this->root);
	}
}