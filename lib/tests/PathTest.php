<?php

namespace tests;

use netPhramework\exceptions\InvalidUri;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\exceptions\PathException;
use netPhramework\exchange\Navigator;
use netPhramework\nodes\Node;
use netPhramework\routing\Route;

class PathTest
{
	private Navigator $navigator;
	private TestNode $testNode;

	public function __construct
	(
	)
	{
		$this->navigator = new Navigator();
	}

	public function run():void
	{
		try {
			$this->testNode = new TestNodeOne();
			echo "\n\n Running basic test...\n";
			$route = $this->testNode->basePath();
			$this->subTest($route);
			echo "\n\n Running fromArray test...\n";
			$route = $this->testNode->fromArray();
			$this->subTest($route);
			echo "\n\n Running fromUri test...\n";
			$route = $this->testNode->fromUri();
			$this->subTest($route);
			echo "\n\n Running fromArrayAndArray test...\n";
			$route = $this->testNode->fromArrayHead()
				->appendPath($this->testNode->fromArrayTail());
			$this->subTest($route);
			echo "\n\n Running fromArrayAndUri test...\n";
			$route = $this->testNode->fromArrayHead()
				->appendPath($this->testNode->fromUriTail());
			$this->subTest($route);
			echo "\n\n Running fromUriAndUri test...\n";
			$route = $this->testNode->fromUriHead()
				->appendPath($this->testNode->fromUriTail());
			$this->subTest($route);
			echo "\n\n Running fromUriAndArray test...\n";
			$route = $this->testNode->fromUriHead()
				->appendPath($this->testNode->fromArrayTail());
			$this->subTest($route);
			echo "\n\n Running fromUriAndArray test...\n";
			$route = $this->testNode->fromUriHead()
				->appendPath($this->testNode->fromArrayTail());
			$this->subTest($route);
			echo "\n\n Running fromCli test...\n";
			$route = $this->testNode->fromCli();
			$this->subTest($route);
			echo "\n\n Running fromUriAndCli test...\n";
			$route = $this->testNode->fromUriHead()
				->appendPath($this->testNode->fromCli());
			$this->subTest($route);
			echo "\n\n Running fromCliAndUri test...\n";
			$route = $this->testNode->fromCli()
				->appendPath($this->testNode->fromUriTail());
			$this->subTest($route);
		} catch (PathException $e) {
			echo $e->getMessage().PHP_EOL;
		}

	}

	/**
	 * @param Route $route
	 * @return void
	 */
	private function subTest(Route $route):void
	{
		try {
			$target = $this->navigator
				->setRoot($this->testNode->getRoot())
				->setRoute($route)
				->navigate();
			$name = $target->getName();
			echo "\n!!!SUCCESS!!! Handler Found: $name\n\n";
		} catch (NodeNotFound $e) {
			$message = $e->getMessage();
			echo "\n***FAILED***: $message\n\n";
		}
	}
}