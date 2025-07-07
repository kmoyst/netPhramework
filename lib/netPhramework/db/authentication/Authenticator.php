<?php

namespace netPhramework\db\authentication;
use netPhramework\authentication\User as UserLoggingIn;
use netPhramework\core\Exception;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\mapping\RecordSet;

class Authenticator implements \netPhramework\authentication\Authenticator
{
	private UserLoggingIn $userLoggingIn;
	private User $user;

	/**
	 * A database backed authentication strategy
	 */
	public function __construct(
		private readonly RecordSet $recordSet,
		?User $user = null) {}

	public function setUserLoggingIn(UserLoggingIn $user): Authenticator
	{
		$this->userLoggingIn = $user;
		return $this;
	}

	public function getHashedUser():UserLoggingIn
	{
		return $this->user; // the hashed user delivers a hashed password
	}

	/**
	 * @return bool
	 * @throws FieldAbsent
	 * @throws Exception
	 */
	public function checkUsername(): bool
	{
		foreach($this->recordSet as $record)
		{
			$user = $this->user ?? new User($record);
			if($user->getUsername() === $this->userLoggingIn->getUsername())
			{
				$this->user = $user;
				return true;
			}
		}
		return false;
	}

	/**
	 * @return bool
	 * @throws Exception
	 * @throws FieldAbsent
	 */
	public function checkPassword(): bool
	{
		return
			isset($this->userLoggingIn) &&
			isset($this->user) &&
			$this->user->checkPassword($this->userLoggingIn->getPassword());
	}
}