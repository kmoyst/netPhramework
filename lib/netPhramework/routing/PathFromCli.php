<?php

namespace netPhramework\routing;

use netPhramework\exceptions\PathException;

class PathFromCli extends Path
{
	private bool $allInputReceived = false;

	/**
	 * @return string|null
	 */
	public function getName():?string
	{
		$this->receiveInput();
		return parent::getName();
	}

	public function getNext(): ?Path
	{
		$this->receiveInput();
		return parent::getNext();
	}

	public function pop(): Path
	{
		$this->receiveInput();
		return parent::pop();
	}

	public function appendPath(Path $tail): Path
	{
		$this->receiveInput();
		return parent::appendPath($tail);
	}


	private function receiveInput():void
	{
		$name = $this->query();
		if($name === '')
		{
			/**
			 * This is interpreted as a resource (leaf) as nodes(composites)
			 * cannot have an empty string as a name
			 */
			$this->setName($node);
			$this->setNext(null);
			return parent::getName();
		}
		elseif($node !== null)
		{
			/**
			 * This will loop and build the request
			 * until an empty string representing resource (leaf)
			 * or a '.' signaling don't append anything
			 */
			echo "\nRequesting node '$node'...\n\n";
			$node = ltrim($node, '/');
			$names = explode('/', $node);
			$this->setName(array_shift($names));
			if (!empty($names)) {
				$next = new PathFromArray($names);
				$this->setNext($next);
				/**
				 * Very important
				 * Don't append a CLI query if
				 * there is a uri form entry with a trailing slash
				 */
				if($names[count($names)-1] !== '')
					$next->appendPath(new PathFromCli());
			} else {
				$this->setNext(new PathFromCli());
			}
		} // otherwise, leave next alone
	}

	private function query():?string
	{
		$name = readline("Enter node name or enter '.' to complete request: ");
		return $name === '.' ? null : $name;
	}
}