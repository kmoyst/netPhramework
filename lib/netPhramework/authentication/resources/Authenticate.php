<?php

namespace netPhramework\authentication\resources;

use netPhramework\authentication\Authenticator;
use netPhramework\authentication\LogInManager;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidPassword;
use netPhramework\exceptions\InvalidUsername;
use netPhramework\exchange\Exchange;
use netPhramework\routing\redirectors\Redirector;
use netPhramework\resources\Resource;

class Authenticate extends Resource
{
	private Authenticator $authenticator;
	private Redirector $onSuccess;
	private Redirector $onFailure;

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

	public function setAuthenticator(Authenticator $authenticator): self
	{
		$this->authenticator = $authenticator;
		return $this;
	}

	public function setOnSuccess(Redirector $onSuccess): self
	{
		$this->onSuccess = $onSuccess;
		return $this;
	}

	public function setOnFailure(Redirector $onFailure): self
	{
		$this->onFailure = $onFailure;
		return $this;
	}
}