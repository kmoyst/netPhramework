<?php

namespace netPhramework\db\authentication\recovery;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
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

class SavePassword extends RecordSetProcess
{
	private readonly Redirector $onFailure;
	private readonly Redirector $onSuccess;

	public function __construct(
		private readonly UserManager $manager,
		?Redirector $onSuccess = null,
		?Redirector $onFailure = null
	)
	{
		$this->onSuccess = $onSuccess ?? new RedirectToRoot('log-in');
		$this->onFailure = $onFailure ?? new RedirectToRoot('log-in');
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
		try {
			$parameters = $exchange->getParameters()
			;
			$user = $this->manager->findByResetCode($parameters);
			$user
				->setPassword($parameters->get($user->fields->password))
				->clearResetCode()
				->save()
			;
			$exchange->getSession()
				->addErrorMessage('New Password Saved')
				->addErrorCode(ResponseCode::OK)
			;
			$exchange->redirect($this->onSuccess);
		} catch (RecordNotFound) {
			$e = new RecordNotFound("User Not Found");
			$exchange->error($e, $this->onFailure);
		} catch (InvalidPassword $e) {
			$exchange->error($e, $this->onFailure);
		}
	}
}