<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\db\presentation\recordTable\columnSet\ColumnSet;
use netPhramework\db\presentation\recordTable\rowSet\CollationMap;
use netPhramework\db\presentation\recordTable\rowSet\RowSetFactory;
use netPhramework\rendering\View;

class RecordTableStrategy
{
	public function configureColumnSet(ColumnSet $columnSet):void {}
	public function configureView(
		View $table, RowSetFactory $factory, CollationMap $map):void {}
}