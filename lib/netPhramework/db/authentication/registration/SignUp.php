<?php

namespace netPhramework\db\authentication\registration;

use netPhramework\db\authentication\UserManager;
use netPhramework\db\resources\RecordSetProcess;
use netPhramework\exceptions\InvalidSession;
use netPhramework\exchange\Exchange;
use netPhramework\locating\ReroutedPath;
use netPhramework\locating\rerouters\Rerouter;
use netPhramework\locating\rerouters\RerouteToSibling;
use netPhramework\presentation\FeedbackView;
use netPhramework\presentation\PasswordInput;
use netPhramework\presentation\TextInput;
use netPhramework\rendering\View;

class SignUp extends RecordSetProcess
{
	public function __construct
	(
	private readonly UserManager $userManager,
	private readonly Rerouter $toSave = new RerouteToSibling('register')
	)
	{}

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
		$formAction    = new ReroutedPath($exchange->path, $this->toSave);
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
}