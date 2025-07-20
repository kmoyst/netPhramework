<?php

namespace netPhramework\data\mysql;

use netPhramework\data\exceptions\MysqlException;

interface Result
{
    public function getAffectedRows():int;
    public function fetchAll():array;
	/**
	 * @return string
	 * @throws MysqlException
	 */
    public function lastInsertId():string;
}