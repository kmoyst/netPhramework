<?php

namespace netPhramework\db\authentication\recovery;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\core\LeafBehaviour;
use netPhramework\core\Node;
use netPhramework\db\authentication\PasswordRecovery as Recovery;
use netPhramework\db\authentication\UserManager;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\exceptions\NotFound;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\locating\redirectors\RedirectToRoot;
use netPhramework\locating\ReroutedPath;
use netPhramework\locating\rerouters\Rerouter;
use netPhramework\locating\rerouters\RerouteToSibling;
use netPhramework\presentation\HiddenInput;
use netPhramework\presentation\PasswordInput;
use netPhramework\rendering\View;

class ChangePassword extends Node
{
	use LeafBehaviour;

	public function __construct
	(
	private readonly UserManager $manager,
	private readonly Rerouter $toSave = new RerouteToSibling('save-password'),
	private readonly Redirector $onNotFound = new RedirectToRoot('log-in')
	)
	{
	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws RecordRetrievalException
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$recovery = new Recovery($this->manager, $exchange->getParameters());
		if(!$recovery->findUser()->userFound())
		{
			$exchange->error(new NotFound(), $this->onNotFound);
			return;
		}
		$resetField    = $recovery->getResetField();
		$resetCode     = $recovery->getResetCode();
		$resetInput	   = new HiddenInput($resetField, $resetCode);
		$passwordInput = new PasswordInput($recovery->getPasswordField());
		$formAction	   = new ReroutedPath($exchange->getPath(), $this->toSave);
		$view = new View('change-password')
			->add('resetCodeInput', $resetInput)
			->add('passwordInput', $passwordInput)
			->add('formAction', $formAction)
			;
		$exchange->ok($view);
	}
}