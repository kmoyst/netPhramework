<?php

namespace netPhramework\data\abstraction\crud;

use netPhramework\data\exceptions\DuplicateEntryException;
use netPhramework\data\exceptions\MappingException;
use netPhramework\data\mapping\DataSet;

interface Insert
{
	public function withData(DataSet $dataSet):Insert;
    /**
     * @return string|null
     * @throws DuplicateEntryException
     * @throws MappingException
     */
    public function confirm():?string;
}