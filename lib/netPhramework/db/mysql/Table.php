<?php

namespace netPhramework\db\mysql;


use netPhramework\db\mysql\queries\Delete;
use netPhramework\db\mysql\queries\Insert;
use netPhramework\db\mysql\queries\Select;
use netPhramework\db\mysql\queries\Update;

readonly class Table implements \netPhramework\db\abstraction\Table
{
    public function __construct(
		private string  $name,
		private Adapter $adapter) {}

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