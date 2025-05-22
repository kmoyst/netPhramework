<?php

namespace netPhramework\db\core;

use netPhramework\core\Exchange;
use netPhramework\core\Leaf;

final class RecordAction extends Leaf
{
	public function __construct(
		private readonly RecordProcess $process,
		private readonly Record $record) { parent::__construct(); }

	public function handleExchange(Exchange $exchange): void
	{
		$this->process->handleExchange($exchange, $this->record);
	}

	public function getName(): string
	{
		return $this->process->getName();
	}
}