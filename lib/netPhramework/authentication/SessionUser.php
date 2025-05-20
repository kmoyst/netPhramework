<?php

namespace netPhramework\authentication;

readonly class SessionUser implements User
{
	public function __construct(private string $username,
								private string $password) {}

	public static function fromArray(array $variables):?SessionUser
	{
		$username = $variables[self::USERNAME_KEY] ?? null;
		$password = $variables[self::PASSWORD_KEY] ?? null;
		return
			$username === null ||
			$password === null ? null : new self($username, $password);
	}

	public static function fromUser(User $user):SessionUser
	{
		return new self($user->getUsername(), $user->getPassword());
	}

	public function updateVariables(array &$variables):void
	{
		$variables[self::USERNAME_KEY] = $this->getUsername();
		$variables[self::PASSWORD_KEY] = $this->getPassword();
	}

	public function getUsername(): string
	{
		return $this->username;
	}

	public function getPassword(): string
	{
		return $this->password;
	}
}