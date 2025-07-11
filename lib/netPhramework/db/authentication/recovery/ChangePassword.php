<?php

namespace netPhramework\db\authentication\recovery;

use netPhramework\db\authentication\PasswordRecovery as Recovery;
use netPhramework\db\authentication\UserManager;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\NotFound;
use netPhramework\exchange\Exchange;
use netPhramework\routing\redirectors\Redirector;
use netPhramework\routing\redirectors\RedirectToRoot;
use netPhramework\routing\ReroutedPath;
use netPhramework\routing\rerouters\Rerouter;
use netPhramework\routing\rerouters\RerouteToSibling;
use netPhramework\presentation\HiddenInput;
use netPhramework\presentation\PasswordInput;
use netPhramework\rendering\View;
use netPhramework\resources\Leaf;

class ChangePassword extends Leaf
{
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
		$recovery = new Recovery($this->manager, $exchange->parameters);
		if(!$recovery->findUser()->userFound())
		{
			$exchange->error(new NotFound(), $this->onNotFound);
			return;
		}
		$resetField    = $recovery->getResetField();
		$resetCode     = $recovery->getResetCode();
		$resetInput	   = new HiddenInput($resetField, $resetCode);
		$passwordInput = new PasswordInput($recovery->getPasswordField());
		$formAction	   = new ReroutedPath($exchange->path, $this->toSave);
		$view = new View('change-password')
			->add('resetCodeInput', $resetInput)
			->add('passwordInput', $passwordInput)
			->add('formAction', $formAction)
			;
		$exchange->ok($view);
	}
}