<?php

namespace netPhramework\authentication;

class SessionUserAggregate implements SessionUser, SessionUserProvider
{
	private string $username;
	private string $password;

	public function __construct(
		private readonly string $usernameKey = 'username',
		private readonly string $passwordKey = 'password') {}

	public function fromUser(User $user):?SessionUser
	{
		$this->setUsername($user->getUsername());
		$this->setPassword($user->getPassword());
		return $this;
	}

	public function fromVariables(array $vars): ?SessionUser
	{
		if(!array_key_exists($this->usernameKey, $vars) ||
			!array_key_exists($this->passwordKey, $vars))
			return null;
		$this->setUsername($vars[$this->usernameKey]);
		$this->setPassword($vars[$this->passwordKey]);
		return $this;
	}

	public function populateVariables(array &$variables):void
	{
		$variables[$this->usernameKey] = $this->getUsername();
		$variables[$this->passwordKey] = $this->getPassword();
	}

	public function getUsername(): string
	{
		return $this->username;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	private function setUsername(string $username): void
	{
		$this->username = $username;
	}

	private function setPassword(string $password): void
	{
		$this->password = $password;
	}
}