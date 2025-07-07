<?php

namespace netPhramework\db\authentication\nodes;

use netPhramework\core\Exchange;
use netPhramework\db\authentication\UserManager;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\exceptions\InvalidSession;
use netPhramework\locating\ReroutedPath;
use netPhramework\locating\rerouters\Rerouter;
use netPhramework\locating\rerouters\RerouteToSibling;
use netPhramework\presentation\FeedbackView;
use netPhramework\presentation\TextInput;
use netPhramework\presentation\PasswordInput;
use netPhramework\rendering\View;

class SignUp extends RecordSetProcess
{
	private readonly Rerouter $toSave;

	public function __construct(
		private readonly UserManager $userManager,
		?Rerouter $toSave = null,
		string    $name = 'sign-up')
	{
		$this->toSave = $toSave ?? new RerouteToSibling('insert');
		$this->name = $name;
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
		$feedbackView  = new FeedbackView($exchange->getSession());
		$formAction    = new ReroutedPath($exchange->getPath(), $this->toSave);
		$usernameInput = new TextInput($user->getFields()->username);
		$passwordInput = new PasswordInput($user->getFields()->password)
		;
		$view = new View('sign-up')
			->add('feedbackView',  $feedbackView)
			->add('formAction',    $formAction)
			->add('usernameInput', $usernameInput)
			->add('passwordInput', $passwordInput)
		;
		$responseCode = $exchange->getSession()->resolveResponseCode()
		;
		$exchange->display($view, $responseCode);
	}
}