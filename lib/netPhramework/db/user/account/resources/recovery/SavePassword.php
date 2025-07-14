<?php

namespace netPhramework\db\user\account\resources\recovery;

use netPhramework\db\user\account\PasswordRecovery as Recovery;
use netPhramework\db\user\UserManager;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidPassword;
use netPhramework\exchange\Exchange;
use netPhramework\exchange\ResponseCode;
use netPhramework\nodes\Resource;
use netPhramework\routing\redirectors\Redirector;

class SavePassword extends Resource
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