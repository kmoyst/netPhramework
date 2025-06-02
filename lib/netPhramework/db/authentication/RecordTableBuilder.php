<?php

namespace netPhramework\db\authentication;
use netPhramework\db\presentation\recordTable\{columns\TextColumn,
	columns\UserRoleColumn,
	RecordTableBuilder as baseBuilder};

class RecordTableBuilder extends baseBuilder
{
	public function __construct(
		private readonly string $usernameField =
		EnrolledUserField::USERNAME->value) {}

	public function buildColumnSet(): baseBuilder
	{
		parent::buildColumnSet();
		$this->columnSet
			->remove('password')
			->add(new TextColumn($this->usernameField))
			->add(new UserRoleColumn())
		;
		return $this;
	}
}