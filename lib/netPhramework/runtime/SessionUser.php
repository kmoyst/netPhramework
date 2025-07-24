<?php

namespace netPhramework\runtime;

use netPhramework\user\User;

interface SessionUser extends User
{
	/**
	 * Meant to populate the session variables once a user has logged in
	 *
	 * @param array $variables
	 * @return void
	 */
	public function populateVariables(array &$variables):void;
}