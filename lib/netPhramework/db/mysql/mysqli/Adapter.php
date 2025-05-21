<?php

namespace netPhramework\db\mysql\mysqli;

use mysqli_sql_exception;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\MysqlException;
use netPhramework\db\mysql\Query;

readonly class Adapter implements \netPhramework\db\mysql\Adapter
{
	public function __construct(private Connection $connection) {}

    /**
     * @param Query $query
     * @return Result
	 * @throws MysqlException
     */
    public function runQuery(Query $query):Result
	{
		try {
			$mySql = $query->getMySql();
			$statement = $this->connection->getLink()->prepare($mySql);
			$dataSet = $query->getDataSet();
			if ($dataSet !== null && count($dataSet) > 0)
			{
				$mapper = new DataMapper();
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