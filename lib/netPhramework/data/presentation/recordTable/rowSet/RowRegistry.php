<?php

namespace netPhramework\data\presentation\recordTable\rowSet;

use netPhramework\data\exceptions\MappingException;
use netPhramework\data\exceptions\RecordNotFound;

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