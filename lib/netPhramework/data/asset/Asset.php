<?php

namespace netPhramework\data\asset;

use netPhramework\common\IsNumeric;
use netPhramework\common\StringPredicate;
use netPhramework\data\asset\AssetNodeSet as NodeSet;
use netPhramework\data\asset\AssetChildNodeSet as ChildNodeSet;
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
	public readonly StringPredicate $predicate    = new IsNumeric(),
	public readonly NodeSet         $nodeSet 	  = new NodeSet(),
	public readonly ChildNodeSet    $childNodeSet = new ChildNodeSet(),
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
	 * @return AssetNode
	 * @throws NodeNotFound
	 */
	private function resolveChild(string $id): AssetNode
	{
		if($this->predicate->test($id))
			return new AssetChildProcessComposite($this->childNodeSet, $id);
		else
			return $this->nodeSet->get($id);
	}
}