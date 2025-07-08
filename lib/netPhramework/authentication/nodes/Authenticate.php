<?php

namespace netPhramework\authentication\nodes;

use netPhramework\authentication\Authenticator;
use netPhramework\authentication\LogInManager;
use netPhramework\core\Node;
use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\core\LeafBehaviour;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\locating\redirectors\RedirectToRoot as toRoot;
use netPhramework\locating\redirectors\RedirectToSibling as toSibling;
use netPhramework\exceptions\InvalidPassword;
use netPhramework\exceptions\InvalidUsername;

class Authenticate extends Node
{
	use LeafBehaviour;

	public function __construct(
		private readonly Authenticator $authenticator,
		private readonly Redirector $onSuccess = new toRoot(),
        private readonly Redirector $onFailure = new toSibling('log-in')
	)
	{}

    /**
     * @param Exchange $exchange
     * @return void
     * @throws Exception
     */
	public function handleExchange(Exchange $exchange): void
    {
        $manager   = new LogInManager();
		$user	   = $manager->userFromVariables($exchange->getParameters());
		$this->authenticator->setUserLoggingIn($user);
		if(!$this->authenticator->checkUsername())
		{
			$username = $user->getUsername();
			$msg = $username ?
				"User not found: $username" :
				"Please enter your username and password to login";
			$exchange->error(new InvalidUsername($msg), $this->onFailure);
		}
		elseif(!$this->authenticator->checkPassword())
		{
			$password = $user->getPassword();
			$msg = $password ?
				"Password incorrect" :
				"Please enter your password";
            $exchange->error(new InvalidPassword($msg), $this->onFailure);
		}
		else
		{
			$user = $this->authenticator->getHashedUser();
			$exchange->getSession()->login($user);
			$exchange->redirect($this->onSuccess);
		}
	}
}