<?php

namespace netPhramework\db\application;

use netPhramework\core\Configuration;
use netPhramework\db\core\RecordMapper;
use netPhramework\resources\Directory;

readonly class Administration implements Configuration
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