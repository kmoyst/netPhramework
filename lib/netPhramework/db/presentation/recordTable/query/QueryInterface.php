<?php

namespace netPhramework\db\presentation\recordTable\query;

interface QueryInterface
{
	public function getConditionSet():array;
	public function getSortArray():array;
	public function getLimit():?int;
	public function getOffset():int;
	public function getCount():int;
}