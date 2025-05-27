<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\Asset;
use netPhramework\db\core\ChildAsset;
use netPhramework\db\core\RecordChildSet;

class RecordChildSetAdapter implements AssetCompositeAdapter
{
	private string $assetLinkField;
	private RecordChildSet $childSet;

	public function setAssetLinkField(string $assetLinkField): self
	{
		$this->assetLinkField = $assetLinkField;
		return $this;
	}

	public function setChildSet(RecordChildSet $childSet): self
	{
		$this->childSet = $childSet;
		return $this;
	}

	public function addAsset(Asset $asset): void
	{
		$this->childSet->add(new ChildAsset($asset, $this->assetLinkField));
	}
}