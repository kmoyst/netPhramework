<?php

namespace netPhramework\authentication\components;

use netPhramework\authentication\LogInManager;
use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\dispatching\RelocateToSibling;
use netPhramework\dispatching\Relocator;
use netPhramework\exceptions\Exception;
use netPhramework\rendering\View;

class LogInPage extends Leaf
{
    public function __construct(
		string $name = 'log-in',
		private readonly ?View $view = null,
		private readonly ?Relocator $forForm = null
    ) { parent::__construct($name); }

    /**
     * @param Exchange $exchange
     * @return void
     * @throws Exception
     */
    public function handleExchange(Exchange $exchange): void
    {
        $manager    = new LogInManager();
        $relocator  = $this->forForm ?? new RelocateToSibling('authenticate');
        $formAction = $exchange->getLocation();
        $relocator->relocate($formAction);
        $errorView    = $exchange->getSession()->getViewableError();
        $responseCode = $exchange->getSession()->resolveResponseCode();
        $view = $this->view ?? new View('log-in-page');
        $view->getVariables()
            ->add('usernameInput', $manager->getUsernameInput())
            ->add('passwordInput', $manager->getPasswordInput())
            ->add('formAction', $formAction)
            ->add('errorView', $errorView ?? '')
            ;
        $exchange->display($view, $responseCode);
    }
}