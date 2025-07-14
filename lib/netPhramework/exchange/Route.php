<?php

namespace netPhramework\exchange;

use netPhramework\core\Application;
use netPhramework\exceptions\Exception;
use netPhramework\nodes\Directory;
use netPhramework\nodes\Node;

readonly class Route
{
	private Directory $root;

	public function __construct(private Application $application)
	{
		$this->root = new Directory('');
	}

	/**
	 * @return Directory
	 * @throws Exception
	 */
    public function toAPassiveNode():Node
	{
		$this->application->configurePassiveNode($this->root);
		return $this->root;
	}

	/**
	 * @return Directory
	 * @throws Exception
	 */
	public function toAnActiveNode():Node
	{
		$this->application->configureActiveNode($this->root);
		return $this->root;
	}
}