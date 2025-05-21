<?php

namespace netPhramework\db\mysql;

use netPhramework\db\mapping\DataSet;

interface Query
{
	public function getMySql():string;
	public function getDataSet():?DataSet;
}