<?php

namespace netPhramework\user;

/**
 * An authentication strategy.
 */
interface Authenticator
{
	/**
	 * Sets the user that is trying to log in for authentication process
	 *
	 * @param UserLoggingIn $user - A user with a plain text password
	 * @return Authenticator
	 */
	public function setUserLoggingIn(User $user):Authenticator;

	/**
	 * @return bool
	 */
	public function checkUsername():bool;

	/**
	 * @return bool
	 */
	public function checkPassword():bool;

	/**
	 * @return User - A logged-in User (usually with hashed password)
	 */
	public function getHashedUser():User;
}