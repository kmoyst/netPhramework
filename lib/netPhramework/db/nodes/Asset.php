<?php

namespace netPhramework\db\nodes;

use netPhramework\common\IsNumeric;
use netPhramework\common\StringPredicate;
use netPhramework\db\core\RecordSet;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\Composite;
use netPhramework\nodes\Node;
use netPhramework\db\nodes\AssetRecordChildSet as RecordChildSet;
use netPhramework\db\nodes\AssetChildSet as ChildSet;

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