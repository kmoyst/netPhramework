<?php

namespace netPhramework\data\mapping;

use Countable;
use Iterator;

interface DataSet extends Iterator, Countable
{
	public function getFieldNames():array;
	public function current(): DataItem;
}