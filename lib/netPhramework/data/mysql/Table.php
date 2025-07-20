<?php

namespace netPhramework\data\mysql;


use netPhramework\data\mysql\crud\Delete;
use netPhramework\data\mysql\crud\Insert;
use netPhramework\data\mysql\crud\Select;
use netPhramework\data\mysql\crud\Update;

readonly class Table implements \netPhramework\data\abstraction\Table
{
    public function __construct(
		private string     $name,
		private Connection $adapter) {}

    public function getName(): string
    {
        return $this->name;
    }

	public function select():Select
	{
		return new Select($this->name, $this->adapter);
	}

	public function update():Update
	{
		return new Update($this->name, $this->adapter);
	}

    public function insert():Insert
    {
		return new Insert($this->name, $this->adapter);
    }

	public function delete():Delete
	{
		return new Delete($this->name, $this->adapter);
	}
}