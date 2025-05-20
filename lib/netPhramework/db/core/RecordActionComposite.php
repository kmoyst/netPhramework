<?php

namespace netPhramework\db\core;

use netPhramework\core\Component;
use netPhramework\core\Composite;

final class RecordActionComposite extends Composite
{
	public function __construct(
		private readonly RecordProcessSet $processSet,
		private readonly Record $record) {}

	public function getChild(string $name): Component
	{
		return new RecordAction(
            $this->processSet->getProcess($name), $this->record);
	}

	public function getName(): string
	{
		return $this->record->getId();
	}
}