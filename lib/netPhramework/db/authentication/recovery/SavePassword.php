<?php

namespace netPhramework\db\authentication\recovery;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\core\LeafTrait;
use netPhramework\core\Node;
use netPhramework\db\authentication\PasswordRecovery as Recovery;
use netPhramework\db\authentication\UserManager;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\exceptions\InvalidPassword;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\locating\redirectors\RedirectToRoot;
use netPhramework\responding\ResponseCode;

class SavePassword implements Node
{
	use LeafTrait;

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
		$recovery = new Recovery($this->manager, $exchange->getParameters());
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
		$exchange->getSession()
			->addFeedbackMessage('New Password Saved')
			->setFeedbackCode(ResponseCode::OK)
		;
		$exchange->redirect($this->onSuccess);
	}
}