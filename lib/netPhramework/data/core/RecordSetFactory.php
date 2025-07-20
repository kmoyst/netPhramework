<?php

namespace netPhramework\data\core;

interface RecordSetFactory
{
	public function recordsFor(string $name):RecordSet;
}