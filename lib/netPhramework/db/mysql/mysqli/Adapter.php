<?php

namespace netPhramework\db\mysql\mysqli;

use mysqli;
use mysqli_sql_exception;
use netPhramework\db\exceptions\MysqlException;
use netPhramework\db\mysql\Query;

class Adapter implements \netPhramework\db\mysql\Adapter
{
    private mysqli $link;

    public function __construct(
		private readonly string $username,
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
			$statement = $this->getLink()->prepare($query->mySql);
			if (!empty($query->data)) {
				$types = $this->mapTypes($query->data);
				$statement->bind_param($types, ...$query->data);
			}
			if (!$statement->execute()) {
				$message = [];
				$message[] = "Query failed: $query->mySql with error: ";
				$message[] = $statement->error;
				$message[] = "Values: " . implode(', ', $query->data ?? []);
				throw new MysqlException(implode(' ', $message));
			}
			return new Result($statement);
		} catch (mysqli_sql_exception $e) {
			throw new MysqlException($e->getMessage());
		}
	}

	private function mapTypes(array $values):string
    {
        $types = [];
        foreach($values as $v)
            $types[] = $this->mapType($v);
        return implode('',$types);
    }

    private function mapType(mixed $value):string
    {
        if(!is_numeric($value)) return 's';
        elseif(is_float($value)) return 'd';
        else return 'i';
    }

    private function getLink():mysqli
    {
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
    }
}