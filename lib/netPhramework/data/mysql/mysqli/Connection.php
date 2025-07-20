<?php

namespace netPhramework\data\mysql\mysqli;

use mysqli;
use mysqli_sql_exception;
use netPhramework\data\exceptions\MysqlException;
use netPhramework\data\mysql\Query;

class Connection implements \netPhramework\data\mysql\Connection
{
	private mysqli $link {get{
		if(!isset($this->link))
		{
			$this->link = new mysqli(
				$this->host,
				$this->username,
				$this->password,
				$this->dbname
			);
		}
		return $this->link;
	}}
	public function __construct(private readonly string $username,
								private readonly string $password,
								private readonly string $dbname,
								private readonly string $host = 'localhost') {}

    /**
     * @param Query $query
     * @return Result
	 * @throws MysqlException
     */
    public function runQuery(Query $query):Result
	{
		try {
			$mySql = $query->getMySql();
			$statement = $this->link->prepare($mySql);
			$dataSet = $query->getDataSet();
			if ($dataSet !== null && count($dataSet) > 0)
			{
				$mapper = new DataItemMapper();
				foreach($query->getDataSet() as $item)
					$mapper->mapItem($item);
				$statement->bind_param(
					$mapper->getBindings()->getTypes(),
					...$mapper->getBindings()->getData());
			}
			if (!$statement->execute()) {
				$message   = [];
				$message[] = "Query failed: $mySql with error: ";
				$message[] = $statement->error;
				throw new MysqlException(implode(' ', $message));
			}
			return new Result($statement);
		} catch (mysqli_sql_exception $e) {
			throw new MysqlException($e->getMessage());
		}
	}
}