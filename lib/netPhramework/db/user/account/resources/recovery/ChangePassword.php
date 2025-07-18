<?php

namespace netPhramework\db\user\account\resources\recovery;

use DateInterval;
use DateMalformedStringException;
use DateTime;
use netPhramework\common\Utils;
use netPhramework\db\user\account\PasswordRecovery as Recovery;
use netPhramework\db\user\UserManager;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\NotFound;
use netPhramework\exchange\Exchange;
use netPhramework\nodes\Resource;
use netPhramework\presentation\HiddenInput;
use netPhramework\presentation\PasswordInput;
use netPhramework\rendering\View;
use netPhramework\routing\redirectors\Redirector;
use netPhramework\routing\PathReroute;
use netPhramework\routing\rerouters\Rerouter;

class ChangePassword extends Resource
{
	private Rerouter $toSave;
	private Redirector $onFailure;
	private UserManager $manager;

	public function __construct () {}

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
			$exchange->error(new NotFound('User not found'), $this->onFailure);
			return;
		}
		try {
			$whenResetCodeWasSet = $recovery->resetTime;
			$isExpired = Utils::isExpired(
				new DateTime($whenResetCodeWasSet),
				new DateInterval('PT1H'));
			if($isExpired)
			{
				$recovery->clearResetCode()->save();
				$exchange->error(
					new NotFound('Code expired'), $this->onFailure);
				return;
			}
		} catch (DateMalformedStringException) {
			$recovery->clearResetCode()->save();
			$exchange->error(new NotFound('Code expired'), $this->onFailure);
			return;
		}
		$resetField    = $recovery->getResetField();
		$resetCode     = $recovery->resetCode;
		$resetInput	   = new HiddenInput($resetField, $resetCode);
		$passwordInput = new PasswordInput($recovery->getPasswordField());
		$formAction	   = new PathReroute($exchange->path, $this->toSave);
		$view = new View('change-password')
			->add('resetCodeInput', $resetInput)
			->add('passwordInput', $passwordInput)
			->add('formAction', $formAction)
			;
		$exchange->ok($view);
	}

	public function setToSave(Rerouter $toSave): self
	{
		$this->toSave = $toSave;
		return $this;
	}

	public function setOnFailure(Redirector $onFailure): self
	{
		$this->onFailure = $onFailure;
		return $this;
	}

	public function setUserManager(UserManager $manager): self
	{
		$this->manager = $manager;
		return $this;
	}
}