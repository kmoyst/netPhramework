<?php

namespace netPhramework\db\presentation\recordTable\columnSet\columns;

use netPhramework\authentication\UserRole;
use netPhramework\db\authentication\EnrolledUserField;
use netPhramework\db\mapping\Record;
use netPhramework\db\presentation\recordTable\columnSet\Column;
use netPhramework\db\presentation\recordTable\columnSet\ColumnHeader;
use netPhramework\rendering\Encodable;

class UserRoleColumn implements Column
{
	public function getName(): string
	{
		return 'role';
	}

	public function getOperationalValue(Record $record): string
	{
		return $this->getSortableValue($record);
	}

	public function getHeader(): ColumnHeader
	{
		return new ColumnHeader('role', 'Role', 200);
	}

	public function getSortableValue(Record $record): string
	{
		return UserRole::tryFrom(
			$record->getValue(
				EnrolledUserField::ROLE->value))->name;
	}

	public function getEncodableValue(Record $record): Encodable|string
	{
		return $this->getSortableValue($record);
	}

}