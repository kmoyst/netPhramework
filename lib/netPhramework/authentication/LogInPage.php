<?php

namespace netPhramework\authentication;

use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\dispatching\RelocateToSibling;
use netPhramework\dispatching\Relocator;
use netPhramework\rendering\View;

class LogInPage extends Leaf
{
    public function __construct(
		string $name = 'log-in',
		private readonly ?View $view = null,
		private readonly ?Relocator $forForm = null
    ) { parent::__construct($this->name); }

    public function handleExchange(Exchange $exchange): void
    {
        $manager    = new LogInManager();
        $relocator  = $this->forForm ?? new RelocateToSibling('authenticate');
        $formAction = $exchange->generateMutableLocation();
        $relocator->relocate($formAction);
        $view = $this->view ?? new View('log-in-page');
        $view->getVariables()
            ->add('usernameInput', $manager->getUsernameInput())
            ->add('passwordInput', $manager->getPasswordInput())
            ->add('formAction', $formAction)
            ;
        $exchange->ok($view);
    }
}