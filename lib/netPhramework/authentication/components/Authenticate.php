<?php

namespace netPhramework\authentication\components;

use netPhramework\authentication\Authenticator;
use netPhramework\authentication\LogInManager;
use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\dispatching\RelocateToSiblingWithMessage as ToSibling;
use netPhramework\dispatching\RelocateWithMessage;
use netPhramework\dispatching\Relocator;
use netPhramework\responding\ResponseCode;

class Authenticate extends Leaf
{
	private string $failedUsernameMessage = 'User not found.';
	private string $failedPasswordMessage = 'Password incorrect.';

	public function __construct(
		private readonly Authenticator $authenticator,
		private readonly ?Relocator $onSuccess = null,
        private readonly ?RelocateWithMessage $onFailure = null)
	{
		parent::__construct('authenticate');
	}

	public function handleExchange(Exchange $exchange): void
    {
        $manager   = new LogInManager();
		$onFailure = $this->onFailure ?? new ToSibling('log-in-status');
		$onSuccess = $this->onSuccess ?? new ToSibling('log-in-status');
		$user	   = $manager->userFromVariables($exchange->getParameters());
		$this->authenticator->setUserLoggingIn($user);
		if(!$this->authenticator->checkUsername())
		{
			$onFailure->setMessage($this->failedUsernameMessage);
			$onFailure->relocate($exchange);
			$exchange->redirect(ResponseCode::UNAUTHORIZED);
		}
		elseif(!$this->authenticator->checkPassword())
		{
			$onFailure->setMessage($this->failedPasswordMessage);
			$onFailure->relocate($exchange);
			$exchange->redirect(ResponseCode::UNAUTHORIZED);
		}
		else
		{
			$user = $this->authenticator->getHashedUser();
			$exchange->getSession()->login($user);
			$onSuccess->relocate($exchange);
			$exchange->seeOther();
		}
	}
}