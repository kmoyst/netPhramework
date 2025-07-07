<?php

namespace netPhramework\db\authentication;
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
use Random\RandomException;

class User implements \netPhramework\authentication\User
{
	private Record $record;
	private UserFieldNames $fields;

	public function __construct(
		Record $record,
		private readonly UserRole $defaultRole = UserRole::STANDARD_USER,
		?UserFieldNames $fields = null)
	{
		$this->record = $record;
		$this->fields = $fields ?? new UserFieldNames();
	}

	public function getFields():UserFieldNames
	{
		return $this->fields;
	}

	/**
	 * @param Variables $vars
	 * @return User|bool|$this
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws InvalidPassword
	 * @throws InvalidValue
	 */
	public function parseAndSet(Variables $vars):self|bool
	{
		if(!$this->confirmInputVarsExist($vars)) return false;
		$this->setUsername($vars->get($this->fields->username));
		$this->setPassword($vars->get($this->fields->password));
		if($this->isNew()) $this->setRole($this->defaultRole);
		return $this;
	}

	private function confirmInputVarsExist(Variables $vars):bool
	{
		return
			$vars->has($this->fields->username) &&
			$vars->has($this->fields->password);
	}

	/**
	 * @param string $fieldName
	 * @return string|null
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function getValue(string $fieldName):?string
	{
		return $this->record->getValue($fieldName);
	}

	/**
	 * @return string
	 * @throws FieldAbsent
	 * @throws AuthenticationException
	 * @throws MappingException
	 */
	public function getUsername():string
	{
		$username = $this->record->getValue($this->fields->username);
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
		$password = $this->record->getValue($this->fields->password);
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
		$role = $this->record->getValue($this->fields->role);
		if($role === null)
			throw new AuthenticationException("Stored Role Is Empty");
		if(UserRole::tryFrom($role) === null)
			throw new AuthenticationException("Stored role is invalid");
		return UserRole::tryFrom($role);
	}

	/**
	 * @return string
	 */
	public function getResetCodeField():string
	{
		return $this->fields->resetCode;
	}

	/**
	 * @return string|null
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function getEmailAddress():?string
	{
		return $this->record->getValue($this->fields->email);
	}

	/**
	 * @return string|null
	 * @throws MappingException
	 */
	public function getFullName():?string
	{
		try {
			$f = $this->getFirstName();
			$l = $this->getLastName();
			if (empty($f) || empty($l)) return null;
			else return "$f $l";
		} catch (FieldAbsent) {
			return null;
		}
	}

	/**
	 * @return string|null
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function getFirstName():?string
	{
		return $this->record->getValue($this->fields->firstName);
	}

	/**
	 * @return string|null
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function getLastName():?string
	{
		return $this->record->getValue($this->fields->lastName);
	}

	/**
	 * @return bool
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function hasEmailAddress():bool
	{
		return !empty($this->getEmailAddress());
	}

	/**
	 * @param string $username
	 * @return $this
	 * @throws FieldAbsent
	 * @throws InvalidValue
	 * @throws MappingException
	 */
	public function setUsername(string $username):self
	{
		$this->record->setValue($this->fields->username, $username);
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
	public function setPassword(string $password):self
	{
		if(strlen($password) < 8)
			throw new InvalidPassword("Password must be at least 8 characters");
		$hash = password_hash($password, PASSWORD_DEFAULT);
		$this->record->setValue($this->fields->password, $hash);
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
		$this->record->setValue($this->fields->role, $role->value);
		return $this;
	}

	/**
	 * @return self
	 * @throws FieldAbsent
	 * @throws InvalidValue
	 * @throws MappingException
	 * @throws RandomException
	 */
	public function newResetCode():self
	{
		$field = $this->fields->resetCode;
		$code = bin2hex(random_bytes(32));
		$this->record->setValue($field, $code);
		return $this;
	}

	/**
	 * @return string|null
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function getResetCode():?string
	{
		return $this->record->getValue($this->fields->resetCode);
	}

	/**
	 * @return $this
	 * @throws FieldAbsent
	 * @throws InvalidValue
	 * @throws MappingException
	 */
	public function clearResetCode():self
	{
		$this->record->setValue($this->fields->resetCode, null);
		return $this;
	}

	/**
	 * @return $this
	 * @throws DuplicateEntryException
	 * @throws InvalidValue
	 * @throws MappingException
	 */
	public function save():User
	{
		$this->record->save();
		return $this;
	}

	public function isNew():bool
	{
		return $this->record->isNew();
	}
}