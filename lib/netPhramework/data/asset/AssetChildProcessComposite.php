<?php

namespace netPhramework\data\asset;

use netPhramework\data\exceptions\MappingException;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\Composite;
use netPhramework\nodes\Node;

class AssetChildProcessComposite extends Composite implements AssetNode
{
	use HasRecordSet;

	public function __construct(
		private readonly AssetChildNodeSet $childSet,
		private readonly string            $recordId
	) {}

	/**
	 * @param string $id
	 * @return Node
	 * @throws MappingException
	 * @throws NodeNotFound
	 */
	public function getChild(string $id): Node
	{
		$record = $this->recordSet->getRecord($this->recordId);
		return $this->childSet->get($id)->setRecord($record);
	}

	public function getName(): string
	{
		return $this->recordId;
	}
}