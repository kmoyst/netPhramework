<?php

namespace netPhramework\db\mysql;

readonly class Query
{
	public function __construct(
		public string $mySql,
		public ?array $data = null) {}
}