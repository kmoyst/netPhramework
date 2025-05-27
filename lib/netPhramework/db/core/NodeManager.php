<?php

namespace netPhramework\db\core;

class NodeManager
{
	private RecordSetNodeSet $recordSetNodeSet;
	private RecordNodeSet 	 $recordNodeSet;

	/**
	 * @param RecordSetNodeSet $recordSetNodeSet
	 * @param RecordNodeSet $recordNodeSet
	 */
	public function __construct(
		RecordSetNodeSet $recordSetNodeSet,
		RecordNodeSet $recordNodeSet)
	{
		$this->recordSetNodeSet = $recordSetNodeSet;
		$this->recordNodeSet = $recordNodeSet;
	}

	public function addRecordNode(RecordNode $node):void
	{
		$this->recordNodeSet->addNode($node);
	}

	public function addRecordSetNode(RecordSetNode $node):void
	{
		$this->recordSetNodeSet->addNode($node);
	}
}