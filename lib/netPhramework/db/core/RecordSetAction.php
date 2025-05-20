<?php

namespace netPhramework\db\core;

use netPhramework\core\Exchange;
use netPhramework\core\Leaf;

final class RecordSetAction extends Leaf
{
	public function __construct(
		private readonly RecordSet        $recordSet,
		private readonly RecordSetProcess $process) { parent::__construct(); }

	public function handleExchange(Exchange $exchange): void
    {
        $this->process->execute($exchange, $this->recordSet);
	}

    public function getName(): string
    {
        return $this->process->getName();
    }
}