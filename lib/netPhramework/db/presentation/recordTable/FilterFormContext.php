<?php

namespace netPhramework\db\presentation\recordTable;

interface FilterFormContext
{
	public function getSortField():?string;
	public function getSortDirection():int;
	public function getLimit():?int;
	public function getOffset():int;
	public function getCount():int;
}