<?php

namespace netPhramework\db\mysql\mysqli;

use mysqli_stmt;
use netPhramework\db\exceptions\MysqlException;

readonly class Result implements \netPhramework\db\mysql\Result
{
	public function __construct(private mysqli_stmt $statement) {}

	public function getAffectedRows():int
	{
		return $this->statement->affected_rows;
	}

	public function fetchAll():array
    {
        return $this->statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }

	public function lastInsertId():string
	{
		$id = $this->statement->insert_id;
		if($id === 0) throw new MysqlException("Insert id is 0");
		return $id;
	}
}