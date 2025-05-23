<?php

namespace netPhramework\authentication\components;

use netPhramework\authentication\Authenticator;
use netPhramework\authentication\LogInManager;
use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\dispatching\Dispatcher;
use netPhramework\dispatching\DispatchToSiblingWithMessage as ToSibling;
use netPhramework\dispatching\DispatchWithMessage;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidPassword;
use netPhramework\exceptions\InvalidUsername;

class Authenticate extends Leaf
{
	public function __construct(
		private readonly Authenticator $authenticator,
		private readonly ?Dispatcher $onSuccess = null,
        private readonly ?Dispatcher $onFailure = null)
	{
		parent::__construct('authenticate');
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
			$exchange->error(
                new InvalidUsername("User not found"), $onFailure);
		}
		elseif(!$this->authenticator->checkPassword())
		{
            $exchange->error(
                new InvalidPassword('Password incorrect'), $onFailure);
		}
		else
		{
			$user = $this->authenticator->getHashedUser();
			$exchange->getSession()->login($user);
			$exchange->redirect($onSuccess);
		}
	}
}