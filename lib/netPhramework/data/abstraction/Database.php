<?php

namespace netPhramework\data\abstraction;

interface Database
{
	public function getSchema(string $name):Schema;
	public function getTable(string $name):Table;
    public function listTables():array;
}