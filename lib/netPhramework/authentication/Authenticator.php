<?php

namespace netPhramework\authentication;

/**
 * An authentication strategy.
 */
interface Authenticator
{
	/**
	 * @param LogInUser $user - A user with a plain text password
	 * @return Authenticator
	 */
	public function setUser(User $user):Authenticator;

	/**
	 * @return bool
	 */
	public function checkUsername():bool;

	/**
	 * @return bool
	 */
	public function checkPassword():bool;

	/**
	 * @return LogInUser - A user with a hashed password
	 */
	public function getUser():User;
}