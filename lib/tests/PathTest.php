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
		$this->navigator = new Navigator()
//			->debugOn()
		;
	}

	public function run():void
	{
		try {
			$this->testNode = new TestNodeOne();
			$this->runAutoTests();
			$this->fromCliTest();
			//$this->fromCliAndUri();
			//$this->fromUriAndCli();
			//$this->fromCliAndArray();
			//$this->fromArrayAndCli();
			//$this->fromCliAndCli();
		} catch (PathException $e) {
			echo $e->getMessage().PHP_EOL;
		}
	}

	/**
	 * @return void
	 * @throws PathException
	 */
	private function runAutoTests():void
	{
		$this->basicTest();
		$this->fromArray();
		$this->fromUri();
		$this->fromArrayAndArray();
		$this->fromArrayAndUri();
		$this->fromUriAndUri();
		$this->fromUriAndArray();
	}

	private function basicTest():void
	{
		echo "\n\n Running basic test...\n";
		$route = $this->testNode->basePath();
		$this->subTest($route);
	}

	private function fromArray():void
	{
		echo "\n\n Running fromArray test...\n";
		$route = $this->testNode->fromArray();
		$this->subTest($route);
	}

	private function fromUri():void
	{
		echo "\n\n Running fromUri test...\n";
		$route = $this->testNode->fromUri();
		$this->subTest($route);
	}

	/**
	 * @return void
	 * @throws PathException
	 */
	private function fromArrayAndArray():void
	{
		echo "\n\n Running fromArrayAndArray test...\n";
		$route = $this->testNode->fromArrayHead()
			->appendPath($this->testNode->fromArrayTail());
		$this->subTest($route);
	}

	/**
	 * @return void
	 * @throws PathException
	 */
	private function fromArrayAndUri():void
	{
		echo "\n\n Running fromArrayAndUri test...\n";
		$route = $this->testNode->fromArrayHead()
			->appendPath($this->testNode->fromUriTail());
		$this->subTest($route);
	}

	/**
	 * @return void
	 * @throws PathException
	 */
	private function fromUriAndUri():void
	{
		echo "\n\n Running fromUriAndUri test...\n";
		$route = $this->testNode->fromUriHead()
			->appendPath($this->testNode->fromUriTail());
		$this->subTest($route);
	}

	/**
	 * @return void
	 * @throws PathException
	 */
	private function fromUriAndArray():void
	{
		echo "\n\n Running fromUriAndArray test...\n";
		$route = $this->testNode->fromUriHead()
			->appendPath($this->testNode->fromArrayTail());
		$this->subTest($route);
	}

	private function fromCliTest():void
	{
		 echo "\n\n Running fromCli test...\n";
		 $route = $this->testNode->fromCli();
		 $this->subTest($route);
	}

	private function fromUriAndCli():void
	{
		echo "\n\n Running fromUriAndCli test...\n";
		$route = $this->testNode->fromUriHead()
			->appendPath($this->testNode->fromCli());
		$this->subTest($route);
	}

	/**
	 * @return void
	 * @throws PathException
	 */
	private function fromCliAndUri():void
	{
		echo "\n\n Running fromCliAndUri test...\n";
		$route = $this->testNode->fromCli()
			->appendPath($this->testNode->fromUriTail());
		$this->subTest($route);
	}

	/**
	 * @return void
	 * @throws PathException
	 */
	private function fromArrayAndCli():void
	{
		echo "\n\n Running fromArrayAndCli test...\n";
		$route = $this->testNode->fromArrayHead()
			->appendPath($this->testNode->fromCli());
		$this->subTest($route);
	}

	/**
	 * @return void
	 * @throws PathException
	 */
	private function fromCliAndArray():void
	{
		echo "\n\n Running fromCliAndArray test...\n\n";
		$route = $this->testNode->fromCli();
		//$route->debugOn = true;
		$route->appendPath($this->testNode->fromArrayTail());
		$this->subTest($route);
	}

	/**
	 * @return void
	 * @throws PathException
	 */
	private function fromCliAndCli():void
	{
		echo "\n\n Running fromCliAndClitest...\n";
		$route = $this->testNode->fromCli()
			->appendPath($this->testNode->fromCli());
		$this->subTest($route);
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