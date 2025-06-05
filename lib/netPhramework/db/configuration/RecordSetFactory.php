<?php

namespace netPhramework\db\configuration;

use netPhramework\db\mapping\RecordSet;

interface RecordSetFactory
{
	public function recordsFor(string $name):RecordSet;
}