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

class EnrolledUser implements User
{
	public function __construct(
		protected readonly Record $record,
		protected string $usernameField = EnrolledUserFields::USERNAME->value,
		protected string $passwordField = EnrolledUserFields::PASSWORD->value
	) {}

	/**
	 * @param Variables $vars
	 * @return EnrolledUser|bool|$this
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws InvalidPassword
	 * @throws InvalidValue
	 */
	public function parseAndSet(Variables $vars):EnrolledUser|bool
	{
		if(!$this->confirmVarsExist($vars)) return false;
		$this->setUsername($vars->get($this->usernameField));
		$this->setPassword($vars->get($this->passwordField));
		return $this;
	}

	private function confirmVarsExist(Variables $vars):bool
	{
		return
			$vars->has($this->usernameField) &&
			$vars->has($this->passwordField);
	}

	/**
	 * @return string
	 * @throws FieldAbsent
	 * @throws AuthenticationException
	 * @throws MappingException
	 */
	public function getUsername():string
	{
		$username = $this->record->getValue($this->usernameField);
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
		$password = $this->record->getValue($this->passwordField);
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
	public function setUsername(string $username):EnrolledUser
	{
		$this->record->getCell($this->usernameField)->setValue($username);
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
	public function setPassword(string $password):EnrolledUser
	{
		if(strlen($password) < 8)
			throw new InvalidPassword("Password must be at least 8 characters");
		$hash = password_hash($password, PASSWORD_DEFAULT);
		$this->record->getCell($this->passwordField)->setValue($hash);
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
	public function save():EnrolledUser
	{
		$this->record->save();
		return $this;
	}
}