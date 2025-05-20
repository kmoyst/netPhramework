<?php

namespace netPhramework\authentication;

use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\dispatching\Dispatcher;
use netPhramework\dispatching\DispatchToRoot;
use netPhramework\exceptions\InvalidPassword;
use netPhramework\exceptions\InvalidUsername;

class Authenticate extends Leaf
{
	public function __construct(
		private readonly Authenticator $authenticator,
		string $name = 'authenticate',
		private readonly ?Dispatcher $onSuccessDispatcher = null)
	{
		parent::__construct($name);
	}

	public function handleExchange(Exchange $exchange): void
	{
		$manager = $this->logInManager ?? new LogInManager();
		$plainUser = $manager->fromVariables($exchange->getParameters());
		$this->authenticator->setUser($plainUser);
		if($this->authenticator->checkUsername() === false)
        {
            $exchange->error(new InvalidUsername("User not found"));
        }
		elseif($this->authenticator->checkPassword() === false)
        {
            $exchange->error(new InvalidPassword("Wrong Password"));
        }
		else
        {
            $exchange->getSession()->login($this->authenticator->getUser());
			($this->onSuccessDispatcher ?? new DispatchToRoot())
				->dispatch($exchange);
        }
	}
}