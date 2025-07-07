<?php

namespace netPhramework\db\authentication;
use netPhramework\db\presentation\recordTable\{columns\TextColumn,
	columns\UserRoleColumn,
	columnSet\ColumnSet,
	columnSet\ColumnSetStrategy as StrategyInterface
};

readonly class ColumnSetStrategy implements StrategyInterface
{
	public function __construct(
		private string $usernameField = UserField::USERNAME->value) {}

	public function configureColumnSet(ColumnSet $columnSet): void
	{
		$columnSet
			->remove('password')
			->add(new TextColumn($this->usernameField))
			->add(new UserRoleColumn())
			;
	}
}