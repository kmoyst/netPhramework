<?php

namespace netPhramework\data\presentation\recordTable;

use netPhramework\data\presentation\recordTable\collation\CollationMap;
use netPhramework\data\presentation\recordTable\rowSet\RowSetFactory;
use netPhramework\rendering\View;

readonly class ViewContext
{
	public function __construct(
		public View $view,
		public RowSetFactory $factory,
		public CollationMap $map) {}
}