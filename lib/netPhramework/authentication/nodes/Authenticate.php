<?php

namespace netPhramework\authentication\nodes;

use netPhramework\authentication\Authenticator;
use netPhramework\authentication\LogInManager;
use netPhramework\core\Node;
use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\core\LeafTrait;
use netPhramework\dispatching\redirectors\Redirector;
use netPhramework\dispatching\redirectors\RedirectToSibling as ToSibling;
use netPhramework\exceptions\InvalidPassword;
use netPhramework\exceptions\InvalidUsername;

class Authenticate implements Node
{
	use LeafTrait;

	public function __construct(
		private readonly Authenticator $authenticator,
		private readonly ?Redirector   $onSuccess = null,
        private readonly ?Redirector   $onFailure = null)
	{
		$this->name = 'authenticate';
	}

    /**
     * @param Exchange $exchange
     * @return void
     * @throws Exception
     */
	public function handleExchange(Exchange $exchange): void
    {
        $manager   = new LogInManager();
		$onFailure = $this->onFailure ?? new ToSibling('log-in');
		$onSuccess = $this->onSuccess ?? new ToSibling('log-in-status');
		$user	   = $manager->userFromVariables($exchange->getParameters());
		$this->authenticator->setUserLoggingIn($user);
		if(!$this->authenticator->checkUsername())
		{
			$username = $user->getUsername();
			$msg = $username ?
				"User not found: $username" :
				"Please enter your username and password to login";
			$exchange->error(new InvalidUsername($msg), $onFailure);
		}
		elseif(!$this->authenticator->checkPassword())
		{
			$password = $user->getPassword();
			$msg = $password ?
				"Password incorrect" :
				"Please enter your password";
            $exchange->error(new InvalidPassword($msg), $onFailure);
		}
		else
		{
			$user = $this->authenticator->getHashedUser();
			$exchange->getSession()->login($user);
			$exchange->redirect($onSuccess);
		}
	}
}