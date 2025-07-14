<?php

namespace netPhramework\db\abstraction\crud;

use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\DataSet;

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