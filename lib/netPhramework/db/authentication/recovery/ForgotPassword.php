<?php

namespace netPhramework\db\authentication\recovery;

use netPhramework\db\authentication\UserManager;
use netPhramework\exceptions\InvalidSession;
use netPhramework\exchange\Exchange;
use netPhramework\routing\ReroutedPath;
use netPhramework\routing\rerouters\Rerouter;
use netPhramework\routing\rerouters\RerouteToSibling as toSibling;
use netPhramework\presentation\FeedbackView;
use netPhramework\rendering\View;
use netPhramework\resources\Leaf;

class ForgotPassword extends Leaf
{
	public function __construct
	(
	private readonly UserManager $manager,
	private readonly Rerouter $toSendLink = new toSibling('send-reset-link')
	)
	{}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws InvalidSession
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$formAction = new ReroutedPath($exchange->path, $this->toSendLink);
		$feedback 	= new FeedbackView($exchange->session)
		;
		$inputName  = $this->manager->fields->username
		;
		$view = new View('forgot-password')
			->add('usernameInputName', $inputName)
			->add('formAction', $formAction)
			->add('feedbackView', $feedback)
		;
		$responseCode = $exchange->session->resolveResponseCode();
		$exchange->display($view, $responseCode);
	}
}