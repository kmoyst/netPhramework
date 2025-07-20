<?php

namespace netPhramework\data\user\administration;
use netPhramework\data\presentation\recordTable\{columnSet\ColumnSetStrategy as StrategyInterface};
use netPhramework\data\presentation\recordTable\columns\EmailColumn;
use netPhramework\data\presentation\recordTable\columns\TextColumn;
use netPhramework\data\presentation\recordTable\columns\UserRoleColumn;
use netPhramework\data\presentation\recordTable\columnSet\ColumnSet;
use netPhramework\data\user\UserField;

readonly class ColumnSetStrategy implements StrategyInterface
{
	public function __construct(
		private string $usernameField = UserField::USERNAME->value) {}

	public function configureColumnSet(ColumnSet $columnSet): void
	{
		$columnSet
			->remove('password')
			->remove('reset-code')
			->remove('reset-code-time')
			->add(new TextColumn($this->usernameField,100))
			->add(new UserRoleColumn())
			->add(new EmailColumn('email-address',150))
			;
	}
}