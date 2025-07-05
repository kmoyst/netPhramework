<?php

namespace netPhramework\authentication\nodes;

use netPhramework\authentication\LogInManager;
use netPhramework\core\Node;
use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\core\LeafTrait;
use netPhramework\locating\rerouters\RerouteToSibling;
use netPhramework\locating\rerouters\Rerouter;
use netPhramework\presentation\FeedbackView;
use netPhramework\rendering\View;

class LogInPage implements Node
{
	use LeafTrait;

    public function __construct(
		string $name = 'log-in',
		private readonly ?View $view = null,
		private readonly ?Rerouter $forForm = null
    ) { $this->name = $name; }

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
            ;
    }
}