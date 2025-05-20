<?php

namespace netPhramework\db\core;

use netPhramework\core\Exchange;

abstract class RecordProcess extends Process
{
	abstract public function execute(Exchange $exchange, Record $record):void;
}