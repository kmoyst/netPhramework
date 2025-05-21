<?php

namespace netPhramework\db\abstraction;

interface Table
{
    public function select():Select;
    public function insert():Insert;
	public function update():Update;
    public function delete():Delete;
}