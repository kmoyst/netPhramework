<?php

namespace netPhramework\data\mysql;

use netPhramework\data\exceptions\MysqlException;

interface Connection
{
	/**
	 * @param Query $query
	 * @return Result
	 * @throws MysqlException
	 */
	public function runQuery(Query $query):Result;
}