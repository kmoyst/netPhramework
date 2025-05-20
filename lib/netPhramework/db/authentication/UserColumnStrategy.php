<?php

namespace netPhramework\db\authentication;

use netPhramework\db\presentation\recordTable\columns\TextColumn;
use netPhramework\db\presentation\recordTable\ColumnSet;
use netPhramework\db\presentation\recordTable\ColumnStrategy;

class UserColumnStrategy implements ColumnStrategy
{
	public function configureColumnSet(ColumnSet $columnSet): void
	{
		$columnSet->clear()
			->add(new TextColumn(EnrolledUser::USERNAME_KEY));
	}
}