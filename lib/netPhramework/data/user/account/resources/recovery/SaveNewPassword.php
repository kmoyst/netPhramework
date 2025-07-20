<?php

namespace netPhramework\data\user\account\resources\recovery;

use netPhramework\data\exceptions\FieldAbsent;
use netPhramework\data\exceptions\MappingException;
use netPhramework\data\exceptions\RecordNotFound;
use netPhramework\data\exceptions\RecordRetrievalException;
use netPhramework\data\user\account\PasswordRecovery as Recovery;
use netPhramework\data\user\UserManager;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidPassword;
use netPhramework\exchange\Exchange;
use netPhramework\exchange\ResponseCode;
use netPhramework\nodes\Resource;
use netPhramework\routing\redirectors\Redirector;

class SaveNewPassword extends Resource
{
	private Redirector $onSuccess;
	private Redirector $onFailure;
	private UserManager $manager;

	public function __construct() {}

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
		try {
			$recovery->findUser();
			if(!$recovery->userFound()) throw new RecordNotFound();
			$recovery
				->clearResetCode()
				->parsePassword(true)
				->save();
		} catch (RecordNotFound) {
			$e = new RecordNotFound("User Not Found");
			$exchange->error($e, $this->onFailure);
		} catch (InvalidPassword $e) {
			$exchange->error($e, $this->onFailure);
		}
		$exchange->session
			->addFeedbackMessage('New Password Saved')
			->setFeedbackCode(ResponseCode::OK)
		;
		$exchange->redirect($this->onSuccess);
	}

	public function setOnSuccess(Redirector $onSuccess): self
	{
		$this->onSuccess = $onSuccess;
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