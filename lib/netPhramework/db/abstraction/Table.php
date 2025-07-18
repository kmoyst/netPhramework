<?php

namespace netPhramework\db\abstraction;

use netPhramework\db\abstraction\crud\Delete;
use netPhramework\db\abstraction\crud\Insert;
use netPhramework\db\abstraction\crud\Select;
use netPhramework\db\abstraction\crud\Update;

interface Table
{
    public function select():Select;
    public function insert():Insert;
	public function update():Update;
    public function delete():Delete;
}