<?php

namespace netPhramework\data\mysql;

use netPhramework\data\mapping\DataSet;

interface Query
{
	public function getMySql():string;
	public function getDataSet():?DataSet;
}