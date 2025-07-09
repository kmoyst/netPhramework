<?php

namespace netPhramework\db\application\mapping;

interface RecordAccess
{
	public function lookupFor(string $name): RecordLookup;
	public function finderFor(string $name): RecordFinder;
	public function optionsFor(
		string $name, RecordDescriber $describer):RecordOptions;
}