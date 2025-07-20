<?php

namespace netPhramework\data\nodes;

use netPhramework\common\IsNumeric;
use netPhramework\common\StringPredicate;
use netPhramework\data\core\RecordSet;
use netPhramework\data\nodes\RecordNodeSet as RecordNodeSet;
use netPhramework\data\nodes\RecordSetNodeSet as NodeSet;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\Composite;
use netPhramework\nodes\Node;

class RecordSetComposite extends Composite implements RecordResourceRegistry
{
	public function __construct
	(
	public readonly string          $name,
	public readonly RecordSet       $recordSet,
	public readonly StringPredicate $predicate     = new IsNumeric(),
	public readonly NodeSet         $nodeSet 	   = new NodeSet(),
	public readonly RecordNodeSet   $recordNodeSet = new RecordNodeSet(),
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
	 * @return RecordSetNode
	 * @throws NodeNotFound
	 */
	private function resolveChild(string $id): RecordSetNode
	{
		if($this->predicate->test($id))
			return new RecordComposite($this->recordNodeSet, $id);
		else
			return $this->nodeSet->get($id);
	}
}