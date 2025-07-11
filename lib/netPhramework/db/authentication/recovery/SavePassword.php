<?php

namespace netPhramework\db\authentication\recovery;

use netPhramework\db\authentication\PasswordRecovery as Recovery;
use netPhramework\db\authentication\UserManager;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidPassword;
use netPhramework\exchange\Exchange;
use netPhramework\exchange\ResponseCode;
use netPhramework\resources\Leaf;
use netPhramework\routing\redirectors\Redirector;
use netPhramework\routing\redirectors\RedirectToRoot;

class SavePassword extends Leaf
{
	public function __construct
	(
	private readonly UserManager $manager,
	private readonly Redirector $onSuccess = new RedirectToRoot('log-in'),
	private readonly Redirector $onFailure = new RedirectToRoot('log-in')
	)
	{}

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
				->parsePassword()
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
}