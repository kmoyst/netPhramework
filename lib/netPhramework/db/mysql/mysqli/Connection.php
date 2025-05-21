<?php

namespace netPhramework\db\mysql\mysqli;

use mysqli;

class Connection
{
	private mysqli $link;

	public function __construct(
		private readonly string $username,
		private readonly string $password,
		private readonly string $dbname,
		private readonly string $host = 'localhost') {}

	public function getLink():mysqli
	{
		if(!isset($this->link))
		{
			$this->link = new mysqli(
				$this->host,
				$this->username,
				$this->password,
				$this->dbname
			);
		}
		return $this->link;
	}
}