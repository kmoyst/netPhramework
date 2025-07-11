<?php

namespace netPhramework\db\application;

use netPhramework\core\NodeBuilder;
use netPhramework\db\core\RecordMapper;
use netPhramework\resources\Directory;

readonly class Administration implements NodeBuilder
{
	public function __construct(protected RecordMapper $mapper) {}

	public function buildPassiveNode(Directory $root): void
	{
		new PassiveTreeBuilder($this->mapper)
			->addAllAssetsWithDefaults($root);
	}

	public function buildActiveNode(Directory $root): void
	{
		new ActiveTreeBuilder($this->mapper)
			->addAllAssetsWithDefaults($root);
	}
}