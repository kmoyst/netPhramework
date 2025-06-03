<?php

namespace netPhramework\db\presentation\recordTable\rowSet;

use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;

interface RowRegistry
{
	/**
	 * @param string $id
	 * @return Row
	 * @throws MappingException
	 * @throws RecordNotFound
	 */
	public function getRow(string $id):Row;
}