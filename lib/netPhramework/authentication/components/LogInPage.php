<?php

namespace netPhramework\authentication\components;

use netPhramework\authentication\LogInManager;
use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\dispatching\relocators\RelocateToSibling;
use netPhramework\dispatching\relocators\Relocator;
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
        $manager    = new LogInManager()
		;
		$formAction = $exchange->getPath();
        $relocator  = $this->forForm??new RelocateToSibling('authenticate');
        $relocator->relocate($formAction)
		;
        $errorView    = $exchange->getSession()->getViewableError();
        $responseCode = $exchange->getSession()->resolveResponseCode()
		;
		$exchange->display($this->view??new View('log-in-page'), $responseCode)
            ->add('usernameInput', 	$manager->getUsernameInput())
            ->add('passwordInput', 	$manager->getPasswordInput())
            ->add('formAction', 	$formAction)
            ->add('errorView', 		$errorView??'')
            ;
    }
}