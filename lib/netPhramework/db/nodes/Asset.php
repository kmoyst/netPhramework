<?php

namespace netPhramework\db\nodes;

use netPhramework\common\IsNumeric;
use netPhramework\common\StringPredicate;
use netPhramework\db\core\RecordSet;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\Composite;
use netPhramework\nodes\Node;

class Asset extends Composite implements AssetResourceSet
{
	public function __construct
	(
	public readonly string          $name,
	public readonly RecordSet       $recordSet,
	public readonly StringPredicate $recordIdPredicate   = new IsNumeric(),
	public readonly AssetChildSet   $assetChildSet = new AssetChildSet(),
	public readonly RecordChildSet  $recordChildSet = new RecordChildSet(),
	) {}

	public function getName(): string
	{
		return $this->name;
	}

	public function getChild(string $id): Node
	{
		return $this->resolveChild($id)->setRecordSet($this->recordSet);
	}

	/**
	 * @param string $id
	 * @return AssetChild
	 * @throws NodeNotFound
	 */
	private function resolveChild(string $id): AssetChild
	{
		if($this->recordIdPredicate->test($id))
			return new RecordComposite($this->recordChildSet, $id);
		else
			return $this->assetChildSet->get($id);
	}
}