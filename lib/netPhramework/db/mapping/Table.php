<?php

namespace netPhramework\db\mapping;

interface Table
{
    public function select():Select;
    public function insert():Insert;
	public function update():Update;
    public function delete():Delete;
}