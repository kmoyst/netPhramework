<?php

namespace netPhramework\db\authentication\nodes;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\authentication\UserManager;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\exceptions\NotFound;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\locating\redirectors\RedirectToRoot;
use netPhramework\locating\rerouters\Rerouter;
use netPhramework\locating\rerouters\RerouteToSibling;
use netPhramework\presentation\HiddenInput;
use netPhramework\presentation\PasswordInput;
use netPhramework\rendering\View;

class ChangePassword extends RecordSetProcess
{
	private readonly Rerouter $formRouter;
	private readonly Redirector $onNotFound;

	public function __construct(
		private readonly UserManager $manager,
		?Rerouter $formRouter = null,
		?Redirector $onNotFound = null
	)
	{
		$this->formRouter = $formRouter?? new RerouteToSibling('save-password');
		$this->onNotFound = $onNotFound?? new RedirectToRoot('log-in');
	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws RecordNotFound
	 * @throws RecordRetrievalException
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$manager		= $this->manager;
		$parameters		= $exchange->getParameters();
		$resetCodeField = $manager->resetCodeFieldName;
		$resetCode 		= $manager->parseForResetCode($parameters);
		if(!$manager->findByResetCode($resetCode))
		{
			$exchange->error(new NotFound(), $this->onNotFound);
			return;
		}
		$resetCodeInput = new HiddenInput($resetCodeField, $resetCode);
		$passwordInput  = new PasswordInput($manager->passwordFieldName);
		$formAction		= $exchange->getPath();
		$this->formRouter->reroute($formAction);
		$view = new View('change-password')
			->add('resetCodeInput', $resetCodeInput)
			->add('passwordInput', $passwordInput)
			->add('formAction', $formAction)
			;
		$exchange->ok($view);
	}
}