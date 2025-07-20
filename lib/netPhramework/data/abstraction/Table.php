<?php

namespace netPhramework\data\abstraction;

use netPhramework\data\abstraction\crud\Delete;
use netPhramework\data\abstraction\crud\Insert;
use netPhramework\data\abstraction\crud\Select;
use netPhramework\data\abstraction\crud\Update;

interface Table
{
    public function select():Select;
    public function insert():Insert;
	public function update():Update;
    public function delete():Delete;
}