<?php

namespace netPhramework\db\core;

use netPhramework\core\Component;
use netPhramework\core\Composite;

class Asset implements Component
{
	use Composite;

	public function __construct(
		private readonly RecordSet        $recordSet,
		private readonly RecordSetNodeSet $recordSetNodeSet,
		private readonly RecordNodeSet    $recordNodeSet) {}

	public function getRecordSet(): RecordSet
	{
		return $this->recordSet;
	}

	public function getChild(string $name): Component
	{
		if(is_numeric($name))
		{
			$composite = new RecordNodeComposite();
			$composite->setRecord($this->recordSet->getRecord($name));
			$composite->setNodeSet($this->recordNodeSet);
			return $composite;
		}
		else
		{
			$process = $this->recordSetNodeSet->getNode($name);
			return $process->setRecordSet($this->recordSet);
		}
	}

	public function getName(): string
	{
		return $this->recordSet->getName();
	}
}