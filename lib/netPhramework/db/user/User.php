<?php

namespace netPhramework\db\user;
use netPhramework\user\UserRole;
use netPhramework\common\Variables;
use netPhramework\db\core\Record;
use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\db\exceptions\MappingException;
use netPhramework\exceptions\AuthenticationException;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidPassword;

class User implements \netPhramework\user\User
{
	private(set) UserRole $role
	{
		get {
			$role = $this->record->getValue($this->fields->role);
			if($role === null)
				throw new AuthenticationException("Stored Role Is Empty");
			if(UserRole::tryFrom($role) === null)
				throw new AuthenticationException("Stored role is invalid");
			return UserRole::tryFrom($role);
		}
		set {}
	}

	private(set) UserProfile $profile{
		get {
			if(!isset($this->profile))
				$this->profile = new UserProfile($this->record, $this->fields);
			return $this->profile;
		}
	}

	public function __construct(
		public readonly Record $record,
		public readonly UserRole $defaultRole,
		public readonly UserFieldNames $fields)
	{}

	/**
	 * @param Variables $vars
	 * @return User|bool|$this
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws InvalidPassword
	 * @throws InvalidValue
	 */
	public function parseRegistration(Variables $vars):self|bool
	{
		if(!$vars->has($this->fields->username) ||
			!$vars->has($this->fields->password)) return false;
		$this->setUsername($vars->get($this->fields->username));
		$this->setPassword($vars->get($this->fields->password), true, true);
		$this->setRole($this->defaultRole);
		return $this;
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
	 * @param bool $encode
	 * @param bool $new
	 * @return $this
	 * @throws FieldAbsent
	 * @throws InvalidPassword
	 * @throws InvalidValue
	 * @throws MappingException
	 */
	public function setPassword(
		string $password, bool $encode = true, bool $new = false):self
	{
		if($new)
		{
			if(strlen($password) < 8)
				throw new InvalidPassword(
					'Password must be at least 8 characters');
			elseif(!preg_match('|[A-Z]|', $password))
				throw new InvalidPassword(
					'Password must contain an uppercase letter');
			elseif(!preg_match('|[a-z]|', $password))
				throw new InvalidPassword(
					'Password must contain a lowercase letter');
			elseif(!preg_match('/[\d\W_]/', $password))
				throw new InvalidPassword(
					'Password must contain a number or special character');
		}
		$value = $encode?password_hash($password, PASSWORD_DEFAULT):$password;
		$this->record->setValue($this->fields->password, $value);
		return $this;
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
	 * @throws InvalidValue
	 * @throws MappingException
	 */
	public function save():User
	{
		$this->record->save();
		return $this;
	}
}