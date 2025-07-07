<?php

namespace netPhramework\db\authentication;

use netPhramework\db\configuration\RecordFinder;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\db\mapping\Record;

readonly class UserManager
{
	public function __construct(
		private RecordFinder $finder,
		private string $resetCodeFieldName = UserField::RESET_CODE->value,
		private string $usernameFieldName = UserField::USERNAME->value
	) {}

	public function getResetCodeFieldName():string
	{
		return $this->resetCodeFieldName;
	}

	public function getUser(Record $record):?User
	{
		return new User($record);
	}

	/**
	 * @param string $by
	 * @param string $value
	 * @return User|null
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws RecordRetrievalException
	 */
	public function findUser(string $by, string $value):?User
	{
		try {
			$record = $this->finder->findUniqueRecord($by, $value);
			return $this->getUser($record);
		} catch (RecordNotFound) {
			return null;
		}
	}

	/**
	 * @param string $resetCode
	 * @return User|null
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws RecordRetrievalException
	 */
	public function findByResetCode(string $resetCode):?User
	{
		return $this->findUser($this->resetCodeFieldName, $resetCode);
	}

	/**
	 * @param string $username
	 * @return User|null
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws RecordRetrievalException
	 */
	public function findByUsername(string $username):?User
	{
		return $this->findUser($this->usernameFieldName, $username);
	}
}