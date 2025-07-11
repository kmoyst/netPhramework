<?php

namespace netPhramework\authentication;

class UserLoggingIn implements User
{
	private string $username;
	private string $password;
	private(set) UserRole $role {get{return UserRole::VISITOR;}}

	public function getUsername(): string
	{
		return $this->username;
	}

	public function setUsername(string $username): UserLoggingIn
	{
		$this->username = $username;
		return $this;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): UserLoggingIn
	{
		$this->password = $password;
		return $this;
	}
}