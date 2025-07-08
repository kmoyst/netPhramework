<?php

namespace netPhramework\authentication\nodes;

use netPhramework\authentication\LogInManager;
use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\locating\rerouters\RerouteToSibling;
use netPhramework\locating\rerouters\Rerouter;
use netPhramework\presentation\FeedbackView;
use netPhramework\rendering\View;

class LogInPage extends Leaf
{
    public function __construct(
		private readonly ?View $view = null,
		private readonly ?Rerouter $forForm = null
    ) {}

	public function getName(): string { return 'log-in'; }

	/**
     * @param Exchange $exchange
     * @return void
     * @throws Exception
     */
    public function handleExchange(Exchange $exchange): void
    {
        $manager    = new LogInManager()
		;
		$formAction = $exchange->getPath();
        $relocator  = $this->forForm??new RerouteToSibling('authenticate');
        $relocator->reroute($formAction)
		;
        $feedbackView = new FeedbackView($exchange->getSession());
        $responseCode = $exchange->getSession()->resolveResponseCode()
		;
		$exchange->display($this->view??new View('log-in-page'), $responseCode)
            ->add('usernameInput', 	$manager->getUsernameInput())
            ->add('passwordInput', 	$manager->getPasswordInput())
            ->add('formAction', 	$formAction)
            ->add('errorView', 		$feedbackView)
			->add('forgotPasswordLink', '/users/forgot-password')
            ;
    }
}