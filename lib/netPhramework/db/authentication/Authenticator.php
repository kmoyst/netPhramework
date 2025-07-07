<?php

namespace netPhramework\db\authentication;
use netPhramework\authentication\User;
use netPhramework\db\authentication\User as DbUser;
use netPhramework\core\Exception;
use netPhramework\db\exceptions\FieldAbsent;

class Authenticator implements \netPhramework\authentication\Authenticator
{
	private User $userLoggingIn;
	private DbUser $dbUser;

	public function __construct
	(
	private readonly UserManager $manager
	)
	{}

	public function setUserLoggingIn(User $user): Authenticator
	{
		$this->userLoggingIn = $user;
		return $this;
	}

	public function getHashedUser():User
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