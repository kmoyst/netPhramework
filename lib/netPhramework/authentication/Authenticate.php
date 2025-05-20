<?php

namespace netPhramework\authentication;

use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\dispatching\RelocateWithMessage;
use netPhramework\dispatching\Relocator;
use netPhramework\responding\ResponseCode;

class Authenticate extends Leaf
{
	private string $failedUsernameMessage = 'User not found.';
	private string $failedPasswordMessage = 'Password incorrect.';

	public function __construct(
		private readonly Authenticator $authenticator,
        string $name = 'authenticate',
        private readonly ?LogInManager $manager = null,
		private readonly ?Relocator $onSuccess = null,
        private readonly ?RelocateWithMessage $onFailure = null)
	{
		parent::__construct($name);
	}

	public function handleExchange(Exchange $exchange): void
    {
        $manager = $this->manager ?? new LogInManager();
		$user	 = $manager->userFromVariables($exchange->getParameters());
		$this->authenticator->setUserLoggingIn($user);
		if(!$this->authenticator->checkUsername())
		{
			$relocator = $this->onFailure ?? new ToLogInStatus();
			$relocator->setMessage($this->failedUsernameMessage);
			$relocator->relocate($exchange);
			$exchange->redirect(ResponseCode::UNAUTHORIZED);
		}
		elseif(!$this->authenticator->checkPassword())
		{
			$relocator = $this->onFailure ?? new ToLogInStatus();
			$relocator->setMessage($this->failedPasswordMessage);
			$relocator->relocate($exchange);
			$exchange->redirect(ResponseCode::UNAUTHORIZED);
		}
		else
		{
			$user = $this->authenticator->getHashedUser();
			$exchange->getSession()->login($user);
			$relocator = $this->onSuccess ?? new ToLogInStatus();
			$relocator->relocate($exchange);
			$exchange->seeOther();
		}
	}
}