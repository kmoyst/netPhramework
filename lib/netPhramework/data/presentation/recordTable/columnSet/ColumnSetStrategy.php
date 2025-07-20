<?php

namespace netPhramework\data\presentation\recordTable\columnSet;

interface ColumnSetStrategy
{
	public function configureColumnSet(ColumnSet $columnSet):void;
}