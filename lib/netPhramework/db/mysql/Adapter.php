<?php

namespace netPhramework\db\mysql;

use netPhramework\db\exceptions\MysqlException;

interface Adapter
{
	/**
	 * @param Query $query
	 * @return Result
	 * @throws MysqlException
	 */
	public function runQuery(Query $query):Result;
}