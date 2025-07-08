<?php

namespace netPhramework\db\authentication\recovery;

use netPhramework\core\Exchange;
use netPhramework\core\LeafTrait;
use netPhramework\core\Node;
use netPhramework\db\authentication\UserManager;
use netPhramework\exceptions\InvalidSession;
use netPhramework\locating\ReroutedPath;
use netPhramework\locating\rerouters\Rerouter;
use netPhramework\locating\rerouters\RerouteToSibling as toSibling;
use netPhramework\presentation\FeedbackView;
use netPhramework\rendering\View;

class ForgotPassword extends Node
{
	use LeafTrait;

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