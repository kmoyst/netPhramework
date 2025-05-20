<?php

namespace netPhramework\authentication;

use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\rendering\View;

class LogInPage extends Leaf
{
	public function __construct(
		?string $name = 'log-in',
		private readonly ?string $title = 'Log In',
		private readonly ?LogInManager $manager = null)
	{ parent::__construct($name); }

	public function handleExchange(Exchange $exchange): void
	{
		$manager = $this->manager ?? new LogInManager()
		;
		$view = new View('log-in-page');
		$view->setTitle($this->title)->getVariables()
			->add('formAction','authenticate')
			->add('usernameInput', $manager->generateUsernameInput())
			->add('passwordInput', $manager->generatePasswordInput())
		;
		$exchange->ok($view);
	}
}