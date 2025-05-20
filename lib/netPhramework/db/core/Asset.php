<?php

namespace netPhramework\db\core;

use netPhramework\core\Component;
use netPhramework\core\Composite;

final class Asset extends Composite
{
	public function __construct(
		private readonly RecordSet           $recordSet,
		private readonly RecordSetProcessSet $processSet,
		private readonly RecordProcessSet    $recordProcessSet) {}

	public function getChild(string $name): Component
    {
        if (is_numeric($name))
            return new RecordActionComposite(
                $this->recordProcessSet,
                $this->recordSet->getRecord($name));
        else
            return new RecordSetAction(
                $this->recordSet,
                $this->processSet->getProcess($name));
    }

    public function getName(): string
    {
        return $this->recordSet->getName();
    }
}