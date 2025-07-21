<?php

namespace netPhramework\user\resources;

use netPhramework\user\LogInManager;
use netPhramework\exchange\Exchange;
use netPhramework\routing\PathReroute;
use netPhramework\routing\rerouters\Rerouter;
use netPhramework\presentation\FeedbackView;
use netPhramework\rendering\View;
use netPhramework\nodes\Resource;

class LogInPage extends Resource
{
	private Rerouter $toAuthenticate;
	private Rerouter $toForgotPassword;

	public function getName(): string { return 'log-in'; }

	/**
     * @param Exchange $exchange
     * @return void
     */
    public function handleExchange(Exchange $exchange): void
    {
        $manager = new LogInManager();
		$formAction = new PathReroute($exchange->path, $this->toAuthenticate);
		$toForgot = new PathReroute($exchange->path, $this->toForgotPassword);
        $feedbackView = new FeedbackView($exchange->session);
        $responseCode = $exchange->session->resolveResponseCode()
		;
		$exchange->display(new View('log-in-page'), $responseCode)
            ->add('usernameInput', 	$manager->getUsernameInput())
            ->add('passwordInput', 	$manager->getPasswordInput())
            ->add('formAction', 	$formAction)
            ->add('errorView', 		$feedbackView)
			->add('forgotPasswordLink', $toForgot)
            ;
    }

	public function setToAuthenticate(Rerouter $toAuthenticate): self
	{
		$this->toAuthenticate = $toAuthenticate;
		return $this;
	}

	public function setToForgotPassword(Rerouter $toForgotPassword): self
	{
		$this->toForgotPassword = $toForgotPassword;
		return $this;
	}
}