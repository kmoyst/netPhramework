<?php

namespace netPhramework\db\mapping;

interface Database
{
	public function getSchema(string $name):Schema;
	public function getTable(string $name):Table;
}