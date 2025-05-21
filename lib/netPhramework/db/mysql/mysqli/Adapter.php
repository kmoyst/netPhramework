<?php

namespace netPhramework\db\mysql\mysqli;

use mysqli;
use mysqli_sql_exception;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\MysqlException;
use netPhramework\db\mapping\DataItem;
use netPhramework\db\mapping\DataSet;
use netPhramework\db\mapping\FieldType;
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
			$mySql = $query->getMySql();
			$statement = $this->getLink()->prepare($mySql);
			if ($query->getDataSet() !== null &&
				count($query->getDataSet()) !== 0)
			{
				$args = $this->getBindArgs($query->getDataSet());
				$statement->bind_param($args->getTypes(), ...$args->getData());
			}
			if (!$statement->execute()) {
				$message = [];
				$message[] = "Query failed: $mySql with error: ";
				$message[] = $statement->error;
//				$message[] = "Values: " . implode(', ', $query->data ?? []);
				throw new MysqlException(implode(' ', $message));
			}
			return new Result($statement);
		} catch (mysqli_sql_exception $e) {
			throw new MysqlException($e->getMessage());
		}
	}

	private function getBindArgs(DataSet $dataSet):BindArgs
	{
		$args = new BindArgs();
		foreach($dataSet as $item) $this->map($args, $item);
		return $args;
	}

	private function map(BindArgs $args, DataItem $item):void
	{
		$value = $item->getValue();
		$args->addQueryValue($value === '' || $value === null ? null : $value);
		switch($item->getField()->getType())
		{
			case FieldType::STRING:
			case FieldType::PARAGRAPH:
			case FieldType::DATE:
			case FieldType::TIME:
				$args->addType('s');
				break;
			case FieldType::BOOLEAN:
			case FieldType::INTEGER:
				$args->addType('i');
				break;
			case FieldType::FLOAT:
				$args->addType('d');
				break;
			default:
				throw new MappingException("Unable to map Field type");
		}
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