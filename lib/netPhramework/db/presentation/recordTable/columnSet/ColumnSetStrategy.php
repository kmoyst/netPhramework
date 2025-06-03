<?php

namespace netPhramework\db\presentation\recordTable\columnSet;

interface ColumnSetStrategy
{
	public function configureColumnSet(ColumnSet $columnSet):void;
}