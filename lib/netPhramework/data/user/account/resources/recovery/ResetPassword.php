<?php

namespace netPhramework\data\user\account\resources\recovery;

use netPhramework\data\user\UserManager;
use netPhramework\exchange\Exchange;
use netPhramework\nodes\Resource;
use netPhramework\presentation\FeedbackView;
use netPhramework\rendering\View;
use netPhramework\routing\PathReroute;
use netPhramework\routing\rerouters\Rerouter;

class ResetPassword extends Resource
{
	private Rerouter $toSendLink;
	private UserManager $manager;

	public function __construct() {}

	/**
	 * @param Exchange $exchange
	 * @return void
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$formAction = new PathReroute($exchange->path, $this->toSendLink);
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

	public function setToSendLink(Rerouter $toSendLink): self
	{
		$this->toSendLink = $toSendLink;
		return $this;
	}

	public function setUserManager(UserManager $manager): self
	{
		$this->manager = $manager;
		return $this;
	}
}