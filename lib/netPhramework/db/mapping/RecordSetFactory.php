<?php

namespace netPhramework\db\mapping;

interface RecordSetFactory
{
	public function recordsFor(string $name):RecordSet;
}