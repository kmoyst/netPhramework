<?php

namespace netPhramework\data\applications;

use netPhramework\core\Application;
use netPhramework\data\builders\ActiveNodeBuilder;
use netPhramework\data\builders\PassiveNodeBuilder;
use netPhramework\data\core\RecordMapper;
use netPhramework\nodes\Directory;

readonly class Administration implements Application
{
	public function __construct(protected RecordMapper $mapper) {}

	public function configurePassiveNode(Directory $root): void
	{
		new PassiveNodeBuilder($this->mapper)
			->addAllAssetsWithDefaults($root);
	}

	public function configureActiveNode(Directory $root): void
	{
		new ActiveNodeBuilder($this->mapper)
			->addAllAssetsWithDefaults($root);
	}
}