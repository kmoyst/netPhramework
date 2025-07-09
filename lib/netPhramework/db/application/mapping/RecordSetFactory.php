<?php

namespace netPhramework\db\application\mapping;

use netPhramework\db\mapping\RecordSet;

interface RecordSetFactory
{
	public function recordsFor(string $name):RecordSet;
}