<?php

namespace netPhramework\db\authentication\nodes;

use netPhramework\core\Exchange;
use netPhramework\db\authentication\User;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\exceptions\InvalidSession;
use netPhramework\presentation\FeedbackView;
use netPhramework\rendering\View;

class ForgotPassword extends RecordSetProcess
{
	public function __construct(
		private readonly ?User  $enrolledUser = null,
		private readonly string $actionLeaf = 'send-reset-link',
		string                  $name = 'forgot-password')
	{
		$this->name = $name;
	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws InvalidSession
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$user = $this->enrolledUser ?? new User();
		$feedbackView = new FeedbackView($exchange->getSession());
		$responseCode = $exchange->getSession()->resolveResponseCode();
		$view = new View('forgot-password')
			->add('usernameInput', $user->getUsernameInput())
			->add('formAction', $this->actionLeaf)
			->add('feedbackView', $feedbackView)
		;
		$exchange->display($view, $responseCode);
	}
}