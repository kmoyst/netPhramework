<?php

namespace netPhramework\db\authentication\resources;

use netPhramework\db\authentication\UserManager;
use netPhramework\db\nodes\RecordSetProcess;
use netPhramework\exceptions\InvalidSession;
use netPhramework\exchange\Exchange;
use netPhramework\presentation\FeedbackView;
use netPhramework\presentation\PasswordInput;
use netPhramework\presentation\TextInput;
use netPhramework\rendering\View;
use netPhramework\routing\ReroutedPath;
use netPhramework\routing\rerouters\Rerouter;

class UserSignUp extends RecordSetProcess
{
	private Rerouter $toRegister;
	private UserManager $userManager;

	public function __construct() {}

	public function getName(): string
	{
		return 'sign-up';
	}

	/**
     * @param Exchange $exchange
     * @return void
     * @throws InvalidSession
     */
	public function handleExchange(Exchange $exchange): void
	{
		$user = $this->userManager->getUser($this->recordSet->newRecord())
		;
		$feedbackView  = new FeedbackView($exchange->session);
		$formAction    = new ReroutedPath($exchange->path, $this->toRegister);
		$usernameInput = new TextInput($user->fields->username);
		$passwordInput = new PasswordInput($user->fields->password)
		;
		$view = new View('sign-up')
			->add('feedbackView',  $feedbackView)
			->add('formAction',    $formAction)
			->add('usernameInput', $usernameInput)
			->add('passwordInput', $passwordInput)
		;
		$responseCode = $exchange->session->resolveResponseCode()
		;
		$exchange->display($view, $responseCode);
	}

	public function setToRegister(Rerouter $toRegister): self
	{
		$this->toRegister = $toRegister;
		return $this;
	}

	public function setUserManager(UserManager $userManager): self
	{
		$this->userManager = $userManager;
		return $this;
	}
}