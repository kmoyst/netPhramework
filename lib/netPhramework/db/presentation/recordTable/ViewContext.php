<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\db\presentation\recordTable\rowSet\CollationMap;
use netPhramework\db\presentation\recordTable\rowSet\RowSetFactory;
use netPhramework\rendering\View;

readonly class ViewContext
{
	public function __construct(
		public View $view,
		public RowSetFactory $factory,
		public CollationMap $map) {}
}