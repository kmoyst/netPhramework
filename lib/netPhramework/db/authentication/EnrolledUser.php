<?php

namespace netPhramework\db\authentication;
use netPhramework\authentication\User;
use netPhramework\authentication\UserRole;
use netPhramework\common\Variables;
use netPhramework\core\Exception;
use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Record;
use netPhramework\exceptions\AuthenticationException;
use netPhramework\exceptions\InvalidPassword;
use netPhramework\presentation\Input;
use netPhramework\presentation\PasswordInput;
use netPhramework\presentation\TextInput;

class EnrolledUser implements User
{
	protected Record $record;

	public function __construct(
		protected EnrolledUserField $usernameField =
		EnrolledUserField::USERNAME,
		protected EnrolledUserField $passwordField =
		EnrolledUserField::PASSWORD,
		protected EnrolledUserField $roleField =
		EnrolledUserField::ROLE,
		protected EnrolledUserField $resetCodeField =
		EnrolledUserField::RESET_CODE
	) {}

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}

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
		if(!$this->confirmInputVarsExist($vars)) return false;
		$this->setUsername($vars->get($this->usernameField->value));
		$this->setPassword($vars->get($this->passwordField->value));
		if($this->isNew()) $this->setRole(UserRole::STANDARD_USER);
		return $this;
	}

	private function confirmInputVarsExist(Variables $vars):bool
	{
		return
			$vars->has($this->usernameField->value) &&
			$vars->has($this->passwordField->value);
	}

	/**
	 * @return string
	 * @throws FieldAbsent
	 * @throws AuthenticationException
	 * @throws MappingException
	 */
	public function getUsername():string
	{
		$username = $this->record->getValue($this->usernameField->value);
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
		$password = $this->record->getValue($this->passwordField->value);
		if($password === null)
			throw new AuthenticationException("Stored Password Is Empty");
		return $password;
	}

	/**
	 * @return UserRole
	 * @throws AuthenticationException
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function getRole(): UserRole
	{
		$role = $this->record->getValue($this->roleField->value);
		if($role === null)
			throw new AuthenticationException("Stored Role Is Empty");
		if(UserRole::tryFrom($role) === null)
			throw new AuthenticationException("Stored role is invalid");
		return UserRole::tryFrom($role);
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
		$this->record->getCell(
			$this->usernameField->value)->setValue($username);
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
		$this->record->getCell($this->passwordField->value)->setValue($hash);
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
     * @param UserRole $role
     * @return $this
     * @throws FieldAbsent
     * @throws InvalidValue
     * @throws MappingException
     */
	public function setRole(UserRole $role):self
	{
		$this->record->getCell($this->roleField->value)->setValue($role->value);
		return $this;
	}

	/**
	 * @return $this
	 * @throws FieldAbsent
	 * @throws InvalidValue
	 * @throws MappingException
	 */
	public function clearResetCode():self
	{
		$this->record->setValue($this->resetCodeField->value, null);
		return $this;
	}

	/**
	 * @return $this
	 * @throws DuplicateEntryException
	 * @throws InvalidValue
	 * @throws MappingException
	 */
	public function save():EnrolledUser
	{
		$this->record->save();
		return $this;
	}

	public function isNew():bool
	{
		return $this->record->isNew();
	}

	public function getUsernameInput():Input
	{
		return new TextInput($this->usernameField->value);
	}

	public function getPasswordInput():Input
	{
		return new PasswordInput($this->passwordField->value);
	}
}