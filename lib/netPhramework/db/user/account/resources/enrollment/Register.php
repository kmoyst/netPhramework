<?php

namespace netPhramework\db\user\account\resources\enrollment;

use netPhramework\db\user\UserManager;
use netPhramework\db\nodes\RecordSetProcess;
use netPhramework\exceptions\InvalidSession;
use netPhramework\exchange\Exchange;
use netPhramework\presentation\FeedbackView;
use netPhramework\presentation\PasswordInput;
use netPhramework\presentation\TextInput;
use netPhramework\rendering\View;
use netPhramework\routing\ReroutedPath;
use netPhramework\routing\rerouters\Rerouter;

class Register extends RecordSetProcess
{
	private Rerouter $formAction;
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
		$formAction    = new ReroutedPath($exchange->path, $this->formAction);
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

	public function setRegisterFormAction(Rerouter $registerFormAction): self
	{
		$this->formAction = $registerFormAction;
		return $this;
	}

	public function setUserManager(UserManager $userManager): self
	{
		$this->userManager = $userManager;
		return $this;
	}
}