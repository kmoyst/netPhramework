<?php

namespace netPhramework\db\authentication\processes;

use netPhramework\core\Exchange;
use netPhramework\db\authentication\EnrolledUser;
use netPhramework\db\core\RecordSet;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\rendering\View;

class SignUp extends RecordSetProcess
{
	public function __construct(
		private readonly ?EnrolledUser $enrolledUser = null,
		private readonly string $actionLeaf = 'insert',
		string $name = 'sign-up')
	{ parent::__construct($name); }

	public function handleExchange(
		Exchange $exchange, RecordSet $recordSet): void
	{
		$user = $this->enrolledUser ?? new EnrolledUser();
		$user->setRecord($recordSet->newRecord());
		$view = new View('sign-up')
			->addVariable('usernameInput', $user->getUsernameInput())
			->addVariable('passwordInput', $user->getPasswordInput())
			->addVariable('formAction', $this->actionLeaf)
		;
		$exchange->ok($view);
	}
}