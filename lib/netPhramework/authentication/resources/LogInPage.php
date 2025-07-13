<?php

namespace netPhramework\authentication\resources;

use netPhramework\authentication\LogInManager;
use netPhramework\exceptions\Exception;
use netPhramework\exchange\Exchange;
use netPhramework\routing\ReroutedPath;
use netPhramework\routing\rerouters\Rerouter;
use netPhramework\presentation\FeedbackView;
use netPhramework\rendering\View;
use netPhramework\nodes\Resource;

class LogInPage extends Resource
{
	private Rerouter $toAuthenticate;
	private Rerouter $toForgotPassword;
	private Rerouter $toSignUp;

	public function getName(): string { return 'log-in'; }

	/**
     * @param Exchange $exchange
     * @return void
     * @throws Exception
     */
    public function handleExchange(Exchange $exchange): void
    {
        $manager = new LogInManager();
		$formAction = new ReroutedPath($exchange->path, $this->toAuthenticate);
		$toForgot = new ReroutedPath($exchange->path, $this->toForgotPassword);
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

	public function setToSignUp(Rerouter $toSignUp): self
	{
		$this->toSignUp = $toSignUp;
		return $this;
	}
}