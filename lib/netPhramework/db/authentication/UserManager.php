<?php

namespace netPhramework\db\authentication;

use netPhramework\common\Variables;
use netPhramework\db\configuration\RecordFinder;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\db\mapping\Record;
use netPhramework\exceptions\NotFound;

readonly class UserManager
{
	public function __construct(
		private RecordFinder $finder,
		public string $resetCodeFieldName = UserField::RESET_CODE->value,
		public string $usernameFieldName = UserField::USERNAME->value,
		public string $passwordFieldName = UserField::PASSWORD->value
	) {}

	public function parseForResetCode(Variables $variables):string|false
	{
		$value = $variables->getOrNull($this->resetCodeFieldName);
		if($value === null) return false;
		else return $value;
	}

	public function parseForUsername(Variables $variables):string|false
	{
		$value = $variables->getOrNull($this->usernameFieldName);
		if($value === null) return false;
		else return $value;
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
	 * @param string|Variables $source
	 * @return User|null
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws RecordRetrievalException
	 * @throws NotFound
	 */
	public function findByResetCode(string|Variables $source):?User
	{
		if($source instanceof Variables)
		{
			$resetCode = $this->parseForResetCode($source);
			if(!$resetCode) throw new NotFound();
		}
		else
		{
			$resetCode = $source;
		}
		return $this->findUser($this->resetCodeFieldName, $resetCode);
	}

	/**
	 * @param string|Variables $source
	 * @return User|null
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws NotFound
	 * @throws RecordRetrievalException
	 */
	public function findByUsername(string|Variables $source):?User
	{
		if($source instanceof Variables)
		{
			$username = $this->parseForUsername($source);
			if(!$username) throw new NotFound();
		}
		else
		{
			$username = $source;
		}
		return $this->findUser($this->usernameFieldName, $username);
	}
}