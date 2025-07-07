<?php

namespace netPhramework\db\authentication\nodes;

use netPhramework\core\Exchange;
use netPhramework\db\authentication\EnrolledUser;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\exceptions\InvalidSession;
use netPhramework\locating\ReroutedPath;
use netPhramework\locating\rerouters\Rerouter;
use netPhramework\locating\rerouters\RerouteToSibling;
use netPhramework\presentation\FeedbackView;
use netPhramework\rendering\View;

class SignUp extends RecordSetProcess
{
	private readonly EnrolledUser $user;
	private readonly Rerouter $toSave;

	public function __construct(
		?Rerouter $toSave = null,
		string $name = 'sign-up',
		?EnrolledUser $user = null)
	{
		$this->user = $user ?? new EnrolledUser();
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
		$feedbackView = new FeedbackView($exchange->getSession());
		$formAction   = new ReroutedPath($exchange->getPath(), $this->toSave);
		;
		$this->user->setRecord($this->recordSet->newRecord())
		;
		$view = new View('sign-up')
			->add('feedbackView',  $feedbackView)
			->add('formAction',    $formAction)
			->add('usernameInput', $this->user->getUsernameInput())
			->add('passwordInput', $this->user->getPasswordInput())
		;
		$responseCode = $exchange->getSession()->resolveResponseCode()
		;
		$exchange->display($view, $responseCode);
	}
}