<?php

namespace netPhramework\db\authentication\presentation;
use netPhramework\db\authentication\UserField;
use netPhramework\db\presentation\recordTable\{columns\EmailColumn,
	columns\TextColumn,
	columns\UserRoleColumn,
	columnSet\ColumnSet,
	columnSet\ColumnSetStrategy as StrategyInterface};

readonly class ColumnSetStrategy implements StrategyInterface
{
	public function __construct(
		private string $usernameField = UserField::USERNAME->value) {}

	public function configureColumnSet(ColumnSet $columnSet): void
	{
		$columnSet
			->remove('password')
			->remove('reset-code')
			->add(new TextColumn($this->usernameField))
			->add(new UserRoleColumn())
			->add(new EmailColumn('email-address'))
			;
	}
}