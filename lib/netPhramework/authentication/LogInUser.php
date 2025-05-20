<?php

namespace netPhramework\authentication;

class LogInUser implements User
{
	private string $username;
	private string $password;

	public function getUsername(): string
	{
		return $this->username;
	}

	public function setUsername(string $username): LogInUser
	{
		$this->username = $username;
		return $this;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): LogInUser
	{
		$this->password = $password;
		return $this;
	}
}