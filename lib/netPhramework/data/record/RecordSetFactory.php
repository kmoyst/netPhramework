<?php

namespace netPhramework\data\record;

interface RecordSetFactory
{
	public function recordsFor(string $name):RecordSet;
}