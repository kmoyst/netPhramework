<?php

namespace netPhramework\db\authentication;

use netPhramework\db\presentation\recordTable\columns\TextColumn;
use netPhramework\db\presentation\recordTable\columns\UserRoleColumn;
use netPhramework\db\presentation\recordTable\ColumnSet;
use netPhramework\db\presentation\recordTable\ColumnStrategy;

readonly class UserColumnStrategy implements ColumnStrategy
{
	public function __construct(
		private string $usernameField =
		EnrolledUserField::USERNAME->value) {}


	public function configureColumnSet(ColumnSet $columnSet): void
	{
		$columnSet->clear()
			->add(new TextColumn($this->usernameField))
			->add(new UserRoleColumn())
		;
	}
}