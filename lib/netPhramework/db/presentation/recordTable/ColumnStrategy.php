<?php

namespace netPhramework\db\presentation\recordTable;

interface ColumnStrategy
{
	public function configureColumnSet(ColumnSet $columnSet):void;
}