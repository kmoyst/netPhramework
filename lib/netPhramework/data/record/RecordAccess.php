<?php

namespace netPhramework\data\record;

interface RecordAccess
{
	public function lookupFor(string $name): RecordLookup;
	public function finderFor(string $name): RecordFinder;
	public function optionsFor(
		string $name, RecordDescriber $describer):RecordOptions;
}