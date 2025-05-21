<?php

namespace netPhramework\db\authentication;
use netPhramework\authentication\User;
use netPhramework\db\core\RecordSet;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\exceptions\Exception;

class Authenticator implements \netPhramework\authentication\Authenticator
{
	private User $userLoggingIn;
	private EnrolledUser $enrolledUser;

	/**
	 * A database backed authentication strategy
	 */
	public function __construct(private readonly RecordSet $recordSet) {}

	public function setUserLoggingIn(User $user): Authenticator
	{
		$this->userLoggingIn = $user;
		return $this;
	}

	public function getHashedUser():User
	{
		return $this->enrolledUser;
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
			$enrolledUser = new EnrolledUser($record);
			if($enrolledUser->getUsername()
				=== $this->userLoggingIn->getUsername())
			{
				$this->enrolledUser = $enrolledUser;
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
			isset($this->enrolledUser) &&
			$this->enrolledUser->checkPassword(
				$this->userLoggingIn->getPassword());
	}
}