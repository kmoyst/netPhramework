<?php

namespace netPhramework\db\core;

use netPhramework\core\Exchange;

abstract class RecordSetProcess extends Process
{
	abstract public function handleExchange(
        Exchange $exchange, RecordSet $recordSet):void;
}