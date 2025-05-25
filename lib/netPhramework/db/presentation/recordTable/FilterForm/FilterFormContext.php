<?php

namespace netPhramework\db\presentation\recordTable\FilterForm;

interface FilterFormContext
{
	public function getSortArray():array;
	public function getLimit():?int;
	public function getOffset():int;
	public function getCount():int;
}