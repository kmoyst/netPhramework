<?php

namespace netPhramework\db\authentication\recovery;

use netPhramework\core\Exchange;
use netPhramework\db\authentication\UserManager;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\exceptions\InvalidSession;
use netPhramework\locating\ReroutedPath;
use netPhramework\locating\rerouters\Rerouter;
use netPhramework\locating\rerouters\RerouteToSibling;
use netPhramework\presentation\FeedbackView;
use netPhramework\rendering\View;

class ForgotPassword extends RecordSetProcess
{
	protected string $name = 'forgot-password';

	public function __construct
	(
	private readonly UserManager $manager,
	private readonly Rerouter $toSendLink = new RerouteToSibling('send-link')
	)
	{}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws InvalidSession
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$formAction = new ReroutedPath($exchange->getPath(),$this->toSendLink);
		$feedback 	= new FeedbackView($exchange->getSession())
		;
		$inputName  = $this->manager->fields->username
		;
		$view = new View('forgot-password')
			->add('usernameInputName', $inputName)
			->add('formAction', $formAction)
			->add('feedbackView', $feedback)
		;
		$responseCode = $exchange->getSession()->resolveResponseCode();
		$exchange->display($view, $responseCode);
	}
}