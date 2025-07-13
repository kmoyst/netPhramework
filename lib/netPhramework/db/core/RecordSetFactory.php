<?php

namespace netPhramework\db\core;

interface RecordSetFactory
{
	public function recordsFor(string $name):RecordSet;
}