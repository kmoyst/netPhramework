<?php

namespace netPhramework\db\configuration\applications;

use netPhramework\configuration\Application;
use netPhramework\db\configuration\builders\ActiveNodeBuilder;
use netPhramework\db\configuration\builders\PassiveNodeBuilder;
use netPhramework\db\core\RecordMapper;
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