<?php

namespace netPhramework\db\mapping;

use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\MappingException;

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