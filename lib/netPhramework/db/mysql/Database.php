<?php

namespace netPhramework\db\mysql;

use netPhramework\db\exceptions\MysqlException;
use netPhramework\db\mysql\queries\TablesQuery;

class Database implements \netPhramework\db\abstraction\Database
{
    private array $schemas = [];
	private array $tables = [];
    private array $tableList;

    public function __construct(private readonly Adapter $adapter) {}

	public function getSchema(string $name):Schema
	{
		if(!isset($this->schemas[$name]))
			$this->schemas[$name] = new Schema($name, $this->adapter);
		return $this->schemas[$name];
	}

	public function getTable(string $name):Table
	{
		if(!isset($this->tables[$name]))
			$this->tables[$name] = new Table($name, $this->adapter);
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
            foreach($this->adapter->runQuery(new TablesQuery())->fetchAll()
                    as $t) $names[] = array_pop($t);
            $this->tableList = $names;
        }
        return $this->tableList;
    }
}