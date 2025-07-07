<?php

namespace netPhramework\db\authentication\nodes;

use netPhramework\common\Variables;
use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\authentication\EnrolledUser;
use netPhramework\db\authentication\EnrolledUserField;
use netPhramework\db\configuration\RecordFinder;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\db\mapping\Record;
use netPhramework\exceptions\InvalidPassword;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\locating\redirectors\RedirectToRoot;
use netPhramework\responding\ResponseCode;

class SavePassword extends RecordSetProcess
{
	private readonly EnrolledUser $enrolledUser;
	private readonly Redirector $onFailure;
	private readonly Redirector $onSuccess;

	public function __construct(
		private readonly RecordFinder $userFinder,
		?Redirector $onSuccess = null,
		?Redirector $onFailure = null,
		?EnrolledUser $enrolledUser = null
	)
	{
		$this->onSuccess = new RedirectToRoot('log-in');
		$this->onFailure = new RedirectToRoot('log-in');
		$this->enrolledUser = $enrolledUser ?? new EnrolledUser();
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
			$parameters   = $exchange->getParameters();
			$record 	  = $this->findRecord($parameters);
			$this->enrolledUser
				->setRecord($record)
				->setPassword(
					$parameters->get(EnrolledUserField::PASSWORD->value))
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

	/**
	 * @param Variables $parameters
	 * @return Record
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws RecordNotFound
	 * @throws RecordRetrievalException
	 */
	private function findRecord(Variables $parameters): Record
	{
		$resetCode = $parameters->get(EnrolledUserField::RESET_CODE->value);
		return $this->userFinder
			->findUniqueRecord(EnrolledUserField::RESET_CODE->value,$resetCode);
	}
}