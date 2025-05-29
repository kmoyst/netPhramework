<?php

namespace netPhramework\authentication;

use netPhramework\common\Variables;
use netPhramework\presentation\Input;
use netPhramework\presentation\PasswordInput;
use netPhramework\presentation\TextInput;

/**
 * Among other things, this class tracks the login page input field names.
 *
 */
readonly class LogInManager
{
	public function __construct(
		private string $usernameKey = 'username',
		private string $passwordKey = 'password') {}


	public function userFromVariables(Variables $variables):User
    {
		$user = new UserLoggingIn();
		$user->setUsername($variables->getOrNull($this->usernameKey));
		$user->setPassword($variables->getOrNull($this->passwordKey));
		return $user;
    }

    public function getUsernameInput():Input
    {
		return new TextInput($this->usernameKey);
    }

    public function getPasswordInput():Input
    {
		return new PasswordInput($this->passwordKey);
    }
}