<?php

namespace netPhramework\db\presentation\recordTable\columns;

use netPhramework\authentication\UserRole;
use netPhramework\db\authentication\UserField;
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

	public function getOperableValue(Record $record): string
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
				UserField::ROLE->value))->friendlyName();
	}

	public function getEncodableValue(Record $record): Encodable|string
	{
		return $this->getSortableValue($record);
	}

}