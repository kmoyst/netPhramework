<?php

namespace netPhramework\db\authentication;
use netPhramework\authentication\User;
use netPhramework\core\Exception;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\mapping\RecordSet;

class Authenticator implements \netPhramework\authentication\Authenticator
{
	private User $userLoggingIn;
	private EnrolledUser $enrolledUser;

	/**
	 * A database backed authentication strategy
	 */
	public function __construct(
		private readonly RecordSet $recordSet,
		?EnrolledUser $enrolledUser = null) {}

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
			$enrolledUser = $this->enrolledUser ?? new EnrolledUser();
			$enrolledUser->setRecord($record);
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