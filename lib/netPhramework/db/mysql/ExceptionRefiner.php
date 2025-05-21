<?php

namespace netPhramework\db\mysql;

use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\MysqlException;

readonly class ExceptionRefiner
{
	public function __construct(private MysqlException $exception) {}

	/**
	 * @return never
	 * @throws MysqlException
	 * @throws DuplicateEntryException
	 */
	public function refineAndThrow():never
	{
		if(preg_match('|^(Duplicate)|', $this->exception->getMessage()))
			throw new DuplicateEntryException($this->exception->getMessage());
		else
			throw $this->exception;
	}
}