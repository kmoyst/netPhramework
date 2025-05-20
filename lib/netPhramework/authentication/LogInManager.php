<?php

namespace netPhramework\authentication;

use netPhramework\common\Variables;
use netPhramework\exceptions\Exception;
use netPhramework\presentation\FormInput\Input;
use netPhramework\presentation\FormInput\PasswordInput;
use netPhramework\presentation\FormInput\TextInput;

readonly class LogInManager
{
	/**
	 * @param Variables $vars
	 * @return User|false
	 * @throws Exception
	 */
	public function fromVariables(Variables $vars): User|false
	{
		if(!$vars->has(User::USERNAME_KEY) || !$vars->has(User::PASSWORD_KEY))
			return false;
		return (new LogInUser())
			->setUsername($vars->get(User::USERNAME_KEY))
			->setPassword($vars->get(User::PASSWORD_KEY))
			;
	}

	public function updateVariables(LogInUser $user, Variables $vars):self
	{
		$vars->add(User::USERNAME_KEY, $user->getUsername());
		$vars->add(User::PASSWORD_KEY, $user->getPassword());
		return $this;
	}

	public function generateUsernameInput():Input
	{
		return new TextInput(User::USERNAME_KEY);
	}

	public function generatePasswordInput():Input
	{
		return new PasswordInput(User::PASSWORD_KEY);
	}
}