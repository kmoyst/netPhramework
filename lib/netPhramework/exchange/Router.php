<?php

namespace netPhramework\exchange;

use netPhramework\core\Application;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\resources\Directory;
use netPhramework\resources\Node;
use netPhramework\routing\Path;

readonly class Router
{
	private Directory $root;

	public function __construct(private Application $application)
	{
		$this->root = new Directory('');
	}


	/**
	 * @return self
	 * @throws Exception
	 */
    public function withAPassiveNode():self
	{
		$this->application->configurePassiveNode($this->root);
		return $this;
	}

	/**
	 * @return self
	 * @throws Exception
	 */
	public function withAnActiveNode():self
	{
		$this->application->configureActiveNode($this->root);
		return $this;
	}

	/**
	 * @param Path $path
	 * @return Node
	 * @throws NodeNotFound
	 */
	public function route(Path $path):Node
	{
		return new Navigator()
			->setRoot($this->root)
			->setPath($path)
			->navigate()
		;
	}
}