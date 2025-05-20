<?php

namespace netPhramework\db\authentication;
use netPhramework\authentication\User;
use netPhramework\common\Variables;
use netPhramework\db\core\Record;
use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\db\exceptions\MappingException;
use netPhramework\exceptions\AuthenticationException;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidPassword;

class UserRecord implements User
{
//	public const string USERNAME_FIELD = 'username';
//	public const string PASSWORD_FIELD = 'password';

	public function __construct(protected readonly Record $record) {}

	/**
	 * @param Variables $vars
	 * @return UserRecord|bool|$this
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws InvalidPassword
	 * @throws InvalidValue
	 */
	public function parseAndSet(Variables $vars):UserRecord|bool
	{
		if(!$this->confirmVarsExist($vars)) return false;
		$this->setUsername($vars->get(self::USERNAME_KEY));
		$this->setPassword($vars->get(self::PASSWORD_KEY));
		return $this;
	}

	public function confirmVarsExist(Variables $vars):bool
	{
		return
			$vars->has(self::USERNAME_KEY) &&
			$vars->has(self::PASSWORD_KEY);
	}

	/**
	 * @return string
	 * @throws FieldAbsent
	 * @throws AuthenticationException
	 * @throws MappingException
	 */
	public function getUsername():string
	{
		$username = $this->record->getValue(self::USERNAME_KEY);
		if($username === null)
			throw new AuthenticationException("Stored username is empty");
		return $username;
	}

	/**
	 * @return string
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws AuthenticationException
	 */
	public function getPassword():string
	{
		$password = $this->record->getValue(self::PASSWORD_KEY);
		if($password === null)
			throw new AuthenticationException("Stored Password Is Empty");
		return $password;
	}

	/**
	 * @param string $username
	 * @return $this
	 * @throws FieldAbsent
	 * @throws InvalidValue
	 * @throws MappingException
	 */
	public function setUsername(string $username):UserRecord
	{
		$this->record->getCell(self::USERNAME_KEY)->setValue($username);
		return $this;
	}

	/**
	 * @param string $password
	 * @return $this
	 * @throws FieldAbsent
	 * @throws InvalidPassword
	 * @throws InvalidValue
	 * @throws MappingException
	 */
	public function setPassword(string $password):UserRecord
	{
		if(strlen($password) < 8)
			throw new InvalidPassword("Password must be at least 8 characters");
		$hash = password_hash($password, PASSWORD_DEFAULT);
		$this->record->getCell(self::PASSWORD_KEY)->setValue($hash);
		return $this;
	}

	/**
	 * @param string $password
	 * @return bool
	 * @throws AuthenticationException
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function checkPassword(string $password):bool
	{
		return password_verify($password, $this->getPassword());
	}

	/**
	 * @return $this
	 * @throws DuplicateEntryException
	 * @throws MappingException
	 */
	public function save():UserRecord
	{
		$this->record->save();
		return $this;
	}
}