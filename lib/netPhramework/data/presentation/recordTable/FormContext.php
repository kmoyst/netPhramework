<?php

namespace netPhramework\data\presentation\recordTable;

interface FormContext
{
	public function getConditionSet():array;
	public function getSortArray():array;
	public function getLimit():?int;
	public function getOffset():int;
	public function getCount():int;
}