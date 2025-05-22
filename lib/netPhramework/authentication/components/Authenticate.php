<?php

namespace netPhramework\authentication\components;

use netPhramework\authentication\Authenticator;
use netPhramework\authentication\LogInManager;
use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\dispatching\Dispatcher;
use netPhramework\dispatching\DispatchToSiblingWithMessage as ToSibling;
use netPhramework\dispatching\DispatchWithMessage;

class Authenticate extends Leaf
{
	private string $failedUsernameMessage = 'User not found.';
	private string $failedPasswordMessage = 'Password incorrect.';

	public function __construct(
		private readonly Authenticator $authenticator,
		private readonly ?Dispatcher $onSuccess = null,
        private readonly ?DispatchWithMessage $onFailure = null)
	{
		parent::__construct('authenticate');
	}

	public function handleExchange(Exchange $exchange): void
    {
        $manager   = new LogInManager();
		$onFailure = $this->onFailure ?? new ToSibling('log-in-failure');
		$onSuccess = $this->onSuccess ?? new ToSibling('log-in-status');
		$user	   = $manager->userFromVariables($exchange->getParameters());
		$this->authenticator->setUserLoggingIn($user);
		if(!$this->authenticator->checkUsername())
		{
			$onFailure->setMessage($this->failedUsernameMessage);
			$exchange->dispatch($onFailure);
		}
		elseif(!$this->authenticator->checkPassword())
		{
			$onFailure->setMessage($this->failedPasswordMessage);
			$exchange->dispatch($onFailure);
		}
		else
		{
			$user = $this->authenticator->getHashedUser();
			$exchange->getSession()->login($user);
			$exchange->dispatch($onSuccess);
		}
	}
}