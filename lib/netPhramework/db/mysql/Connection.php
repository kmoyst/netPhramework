<?php

namespace netPhramework\db\mysql;

use netPhramework\db\exceptions\MysqlException;

interface Connection
{
	/**
	 * @param Query $query
	 * @return Result
	 * @throws MysqlException
	 */
	public function runQuery(Query $query):Result;
}