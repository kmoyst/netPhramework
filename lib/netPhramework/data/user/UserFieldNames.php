<?php

namespace netPhramework\data\user;

readonly class UserFieldNames
{
	public function __construct(
		public string $username  = UserField::USERNAME->value,
		public string $password  = UserField::PASSWORD->value,
		public string $role		 = UserField::ROLE->value,
		public string $resetCode = UserField::RESET_CODE->value,
		public string $resetTime = UserField::RESET_TIME->value,
		public string $email	 = UserField::EMAIL->value,
		public string $firstName = UserField::FIRST_NAME->value,
		public string $lastName  = UserField::LAST_NAME->value
	) {}
}