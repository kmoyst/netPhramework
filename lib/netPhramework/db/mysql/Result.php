<?php

namespace netPhramework\db\mysql;

use netPhramework\db\exceptions\MysqlException;

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