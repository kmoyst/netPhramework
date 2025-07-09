<?php

namespace netPhramework\core;

use netPhramework\exceptions\ResourceNotFound;

class Directory extends Composite implements BuildableResource
{
	protected string|Index $autoIndexer;

	public function __construct(
		public readonly string $name,
		protected ResourceSet $children = new ResourceSet()
	) {}

	public function add(Resource $node):self
	{
		$this->children->add($node);
		return $this;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getChild(string $id): Resource
	{
		if($this->children->has($id)) return $this->children->get($id);
		else return $this->autoIndexIfPermitted($id);
	}

	/**
	 * @param string $id
	 * @return Index
	 * @throws ResourceNotFound
	 */
	protected function autoIndexIfPermitted(string $id):Index
	{
		if($id === '' && isset($this->autoIndexer))
		{
			return (is_string($this->autoIndexer) ?
				new Index($this->autoIndexer) : $this->autoIndexer)
				->setNodeSet($this->children);
		}
		else
		{
			throw new ResourceNotFound("Not Found: $id");
		}
	}

	/**
	 * Allows dynamic creation and return of an Index with reference to the
	 * child components if no leaf exists with name '' (empty string)
	 *
	 * If $index is null, uses a default Index with default template. If it
	 * is a string, it uses the default Index but with the supplied template.
	 * Otherwise, it will use the custom Index object provided.
	 *
	 * @param string|Index|null $indexer
	 * @return void
	 */
	public function permitAutoIndex(string|Index|null $indexer = null):void
	{
		$this->autoIndexer = $indexer ?? 'index';
	}
}