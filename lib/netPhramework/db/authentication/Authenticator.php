<?php

namespace netPhramework\db\authentication;
use netPhramework\authentication\User;
use netPhramework\db\core\RecordSet;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\exceptions\Exception;

class Authenticator implements \netPhramework\authentication\Authenticator
{
	private User $user;
	private UserRecord $userRecord;

	/**
	 * A database backed authentication strategy
	 */
	public function __construct(private readonly RecordSet $userRecords) {}

	public function setUser(User $user): Authenticator
	{
		$this->user = $user;
		return $this;
	}

	public function getUser():User
	{
		return $this->userRecord;
	}

	/**
	 * @return bool
	 * @throws FieldAbsent
	 * @throws Exception
	 */
	public function checkUsername(): bool
	{
		foreach($this->userRecords as $record)
		{
			$userRecord = new UserRecord($record);
			if($userRecord->getUsername() === $this->user->getUsername())
			{
				$this->userRecord = $userRecord;
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
			isset($this->user) &&
			isset($this->userRecord) &&
			$this->userRecord->checkPassword($this->user->getPassword());
	}
}