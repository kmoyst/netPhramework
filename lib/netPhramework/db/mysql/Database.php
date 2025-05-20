<?php

namespace netPhramework\db\mysql;

class Database implements \netPhramework\db\mapping\Database
{
    private array $schemas = [];
	private array $tables = [];

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
}