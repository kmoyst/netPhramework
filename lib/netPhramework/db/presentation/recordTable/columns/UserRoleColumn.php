<?php

namespace netPhramework\db\presentation\recordTable\columns;

use netPhramework\authentication\UserRole;
use netPhramework\db\authentication\EnrolledUserField;
use netPhramework\db\core\Record;
use netPhramework\db\presentation\recordTable\Column;
use netPhramework\db\presentation\recordTable\ColumnHeader;
use netPhramework\rendering\Viewable;

class UserRoleColumn implements Column
{
	public function getName(): string
	{
		return 'role';
	}

	public function getHeader(): ColumnHeader
	{
		return new ColumnHeader('role', 'Role', 50);
	}

	public function getSortableValue(Record $record): string
	{
		return UserRole::tryFrom(
			$record->getValue(
				EnrolledUserField::ROLE->value))->name;
	}

	public function getViewableValue(Record $record): Viewable|string
	{
		return $this->getSortableValue($record);
	}

}