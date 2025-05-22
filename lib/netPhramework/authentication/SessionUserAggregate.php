<?php

namespace netPhramework\authentication;

use netPhramework\exceptions\AuthenticationException;

/**
 * Among other things, this class tracks what keys are used to store the
 * user info into the session.
 *
 */
class SessionUserAggregate implements SessionUser, SessionUserProvider
{
	private string $username;
	private string $password;
	private UserRole $role;

	public function __construct(
		private readonly string $usernameKey = 'username',
		private readonly string $passwordKey = 'password',
		private readonly string $roleKey = 'role') {}

	public function fromUser(User $user):?SessionUser
	{
		$this->setUsername($user->getUsername());
		$this->setPassword($user->getPassword());
		$this->setRole($user->getRole());
		return $this;
	}

	/**
	 * @param array $vars
	 * @return SessionUser|null
	 * @throws AuthenticationException
	 */
	public function fromArray(array $vars): ?SessionUser
	{
		if(!array_key_exists($this->usernameKey, $vars) ||
			!array_key_exists($this->passwordKey, $vars) ||
			!array_key_exists($this->roleKey, $vars))
			return null;
		$this->setUsername($vars[$this->usernameKey]);
		$this->setPassword($vars[$this->passwordKey]);
		$role = UserRole::tryFrom($vars[$this->roleKey]);
		if($role === null)
			throw new AuthenticationException('Invalid role in session');
		$this->setRole($role);
		return $this;
	}

	public function populateVariables(array &$variables):void
	{
		$variables[$this->usernameKey] = $this->getUsername();
		$variables[$this->passwordKey] = $this->getPassword();
		$variables[$this->roleKey] = $this->getRole()->value;
	}

	public function getUsername(): string
	{
		return $this->username;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function getRole(): UserRole
	{
		return $this->role;
	}

	private function setUsername(string $username): void
	{
		$this->username = $username;
	}

	private function setPassword(string $password): void
	{
		$this->password = $password;
	}

	private function setRole(UserRole $role): self
	{
		$this->role = $role;
		return $this;
	}
}