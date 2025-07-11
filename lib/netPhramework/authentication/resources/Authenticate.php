<?php

namespace netPhramework\authentication\resources;

use netPhramework\authentication\Authenticator;
use netPhramework\authentication\LogInManager;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidPassword;
use netPhramework\exceptions\InvalidUsername;
use netPhramework\exchange\Exchange;
use netPhramework\routing\redirectors\Redirector;
use netPhramework\routing\redirectors\RedirectToRoot as toRoot;
use netPhramework\routing\redirectors\RedirectToSibling as toSibling;
use netPhramework\resources\Leaf;

class Authenticate extends Leaf
{
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
		$user	   = $manager->userFromVariables($exchange->parameters);
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
			$exchange->session->login($user);
			$exchange->redirect($this->onSuccess);
		}
	}
}