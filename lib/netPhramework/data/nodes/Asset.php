<?php

namespace netPhramework\data\nodes;

use netPhramework\common\IsNumeric;
use netPhramework\common\StringPredicate;
use netPhramework\data\nodes\AssetChildSet as ChildSet;
use netPhramework\data\nodes\AssetRecordChildSet as RecordChildSet;
use netPhramework\data\record\RecordSet;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\Composite;
use netPhramework\nodes\Node;

class Asset extends Composite implements AssetResourceRegistry
{
	public function __construct
	(
	public readonly string          $name,
	public readonly RecordSet       $recordSet,
	public readonly StringPredicate $predicate 		= new IsNumeric(),
	public readonly ChildSet        $assetChildSet 	= new ChildSet(),
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
		if($this->predicate->test($id))
			return new AssetRecord($this->recordChildSet, $id);
		else
			return $this->assetChildSet->get($id);
	}
}