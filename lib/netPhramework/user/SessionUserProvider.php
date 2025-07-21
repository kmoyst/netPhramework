<?php

namespace netPhramework\user;

use netPhramework\exceptions\InvalidRoleInSession;

interface SessionUserProvider
{
	/**
	 * Meant to add a user logging in to the Session.
	 *
	 * @param User $user
	 * @return SessionUser|null
	 */
	public function fromUser(User $user):?SessionUser;

	/**
	 * Meant to be supplied by Session when reinstantiating the user
	 *
	 * @param array $vars
	 * @return SessionUser|null
     * @throws InvalidRoleInSession
	 */
	public function fromArray(array $vars):?SessionUser;
}