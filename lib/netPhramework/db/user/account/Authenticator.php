<?php

namespace netPhramework\db\user\account;
use netPhramework\user\User as BaseUser;
use netPhramework\db\user\User as DbUser;
use netPhramework\db\user\UserManager;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\exceptions\Exception;

class Authenticator implements \netPhramework\user\Authenticator
{
	private BaseUser $userLoggingIn;
	private DbUser $dbUser;

	public function __construct
	(
	private readonly UserManager $manager
	)
	{}

	public function setUserLoggingIn(BaseUser $user): Authenticator
	{
		$this->userLoggingIn = $user;
		return $this;
	}

	public function getHashedUser():BaseUser
	{
		return $this->dbUser;
	}

	/**
	 * @return bool
	 * @throws FieldAbsent
	 * @throws Exception
	 */
	public function checkUsername(): bool
	{
		$username = $this->userLoggingIn->getUsername();
		$user	  = $this->manager->findByUsername($username);
		if($user instanceof DbUser)
		{
			$this->dbUser = $user;
			return true;
		}
		else
		{
			return false;
		}
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
			isset($this->dbUser) &&
			$this->dbUser->checkPassword($this->userLoggingIn->getPassword());
	}
}