<?php

namespace netPhramework\db\authentication\nodes;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\authentication\User;
use netPhramework\db\authentication\UserField;
use netPhramework\db\configuration\RecordFinder;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\locating\redirectors\RedirectToRoot;
use netPhramework\locating\rerouters\Rerouter;
use netPhramework\locating\rerouters\RerouteToSibling;
use netPhramework\presentation\HiddenInput;
use netPhramework\rendering\View;

class ChangePassword extends RecordSetProcess
{
	private readonly User $enrolledUser;
	private readonly Rerouter $formRouter;
	private readonly Redirector $onNotFound;

	public function __construct(
		private readonly RecordFinder $userFinder,
		?Rerouter $formRouter = null,
		?Redirector $onNotFound = null,
		?User $enrolledUser = null
	)
	{
		$this->formRouter = $formRouter?? new RerouteToSibling('save-password');
		$this->onNotFound = $onNotFound?? new RedirectToRoot('log-in');
		$this->enrolledUser = $enrolledUser?? new User();
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
		$parameters		= $exchange->getParameters();
		$resetCodeField = UserField::RESET_CODE->value;
		$resetCode 		= $parameters->get($resetCodeField);
		try {
			$this->userFinder->findUniqueRecord($resetCodeField, $resetCode);
		} catch (RecordNotFound) {
			$e = new RecordNotFound('Invalid Reset Code');
			$exchange->error($e, $this->onNotFound);
			return;
		}
		$resetCodeInput = new HiddenInput($resetCodeField, $resetCode);
		$formAction		= $exchange->getPath();
		$this->formRouter->reroute($formAction);
		$view = new View('change-password')
			->add('resetCodeInput', $resetCodeInput)
			->add('passwordInput', $this->enrolledUser->getPasswordInput())
			->add('formAction', $formAction)
			;
		$exchange->ok($view);
	}
}