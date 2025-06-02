<?php

namespace netPhramework\db\presentation\recordTable\filterForm;

interface FilterFormContext
{
	public function getConditionSet():array;
	public function getSortArray():array;
	public function getLimit():?int;
	public function getOffset():int;
	public function getCount():int;
}