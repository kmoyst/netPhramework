<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\RecordNode;
use netPhramework\db\core\RecordNodeSet;
use netPhramework\db\core\RecordSetNode;
use netPhramework\db\core\RecordSetNodeSet;

class AssetNodeManager
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