<?php

namespace netPhramework\data\presentation\recordTable\columns;

use netPhramework\data\core\Record;
use netPhramework\data\presentation\recordTable\columnSet\Column;
use netPhramework\data\presentation\recordTable\columnSet\ColumnHeader;
use netPhramework\data\user\UserField;
use netPhramework\rendering\Encodable;
use netPhramework\user\UserRole;

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
		return new ColumnHeader('role', 'Role', 100);
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