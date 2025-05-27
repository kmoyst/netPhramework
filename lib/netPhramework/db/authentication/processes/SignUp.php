<?php

namespace netPhramework\db\authentication\processes;

use netPhramework\core\Exchange;
use netPhramework\db\authentication\EnrolledUser;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\exceptions\InvalidSession;
use netPhramework\rendering\View;

class SignUp extends RecordSetProcess
{
	public function __construct(
		private readonly ?EnrolledUser $enrolledUser = null,
		private readonly string $actionLeaf = 'insert',
		string $name = 'sign-up')
	{ parent::__construct($name); }

    /**
     * @param Exchange $exchange
     * @return void
     * @throws InvalidSession
     */
	public function handleExchange(Exchange $exchange): void
	{
		$user = $this->enrolledUser ?? new EnrolledUser();
		$user->setRecord($this->recordSet->newRecord());
        $errorView    = $exchange->getSession()->getEncodableValue();
        $responseCode = $exchange->getSession()->resolveResponseCode();
        $view = new View('sign-up')
			->add('usernameInput', $user->getUsernameInput())
			->add('passwordInput', $user->getPasswordInput())
			->add('formAction', $this->actionLeaf)
            ->add('errorView', $errorView ?? '')
        ;
		$exchange->display($view, $responseCode);
	}
}