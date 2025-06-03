<?php

namespace netPhramework\db\authentication;
use netPhramework\db\presentation\recordTable\{columns\TextColumn,
	columns\UserRoleColumn,
	columnSet\ColumnSet,
	RecordTableStrategy as base};

class RecordTableStrategy extends base
{
	public function __construct(
		private readonly string $usernameField =
		EnrolledUserField::USERNAME->value) {}

	public function configureColumnSet(ColumnSet $columnSet): void
	{
		$columnSet
			->remove('password')
			->add(new TextColumn($this->usernameField))
			->add(new UserRoleColumn())
			;
	}

}