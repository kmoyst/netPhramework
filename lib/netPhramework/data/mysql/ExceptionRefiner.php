<?php

namespace netPhramework\data\mysql;

use netPhramework\data\exceptions\DuplicateEntryException;
use netPhramework\data\exceptions\MysqlException;

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