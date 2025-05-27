<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\Asset;
use netPhramework\db\core\RecordChild;

class RecordChildAdapter implements CompositeAdapter
{
	private RecordChild $child;

	public function __construct(RecordChild $child)
	{
		$this->child = $child;
	}

	public function addAsset(Asset $asset): void
	{
		$this->child->setAsset($asset);
	}
}