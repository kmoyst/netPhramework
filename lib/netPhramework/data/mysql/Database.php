<?php

namespace netPhramework\data\mysql;

use netPhramework\data\exceptions\MysqlException;
use netPhramework\data\mysql\queries\ShowTables;

class Database implements \netPhramework\data\abstraction\Database
{
    private array $schemas = [];
	private array $tables = [];
    private array $tableList;

    public function __construct(private readonly Connection $connection) {}

	public function getSchema(string $name):Schema
	{
		if(!isset($this->schemas[$name]))
			$this->schemas[$name] = new Schema($name, $this->connection);
		return $this->schemas[$name];
	}

	public function getTable(string $name):Table
	{
		if(!isset($this->tables[$name]))
			$this->tables[$name] = new Table($name, $this->connection);
		return $this->tables[$name];
	}

    /**
     * @return array
     * @throws MysqlException
     */
    public function listTables(): array
    {
        if(!isset($this->tableNames))
        {
            $names = [];
            foreach($this->connection->runQuery(new ShowTables())->fetchAll()
                    as $t) $names[] = array_pop($t);
            $this->tableList = $names;
        }
        return $this->tableList;
    }
}