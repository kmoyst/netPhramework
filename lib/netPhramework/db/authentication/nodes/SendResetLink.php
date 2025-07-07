<?php

namespace netPhramework\db\authentication\nodes;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\authentication\EnrolledUser;
use netPhramework\db\authentication\EnrolledUserField;
use netPhramework\db\authentication\UserProfile;
use netPhramework\db\configuration\RecordFinder;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\db\mapping\Record;
use netPhramework\locating\Location;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\locating\redirectors\RedirectToRoot;
use netPhramework\locating\rerouters\Rerouter;
use netPhramework\locating\rerouters\RerouteToSibling;
use netPhramework\locating\UriFromLocation;
use netPhramework\networking\EmailDelivery;
use netPhramework\networking\EmailException;
use netPhramework\networking\StreamSocketException;
use netPhramework\responding\ResponseCode;
use Random\RandomException;

class SendResetLink extends RecordSetProcess
{
	private readonly Rerouter $toChangePassword;
	private readonly Redirector $afterProcess;
	private readonly string $resetCodeField;

	public function __construct(
		private readonly RecordFinder $userRecords,
		private readonly string $sender,
		private readonly ?string $senderName = null,
		?Rerouter $toChangePassword = null,
		?Redirector $afterProcess = null
	)
	{
		$this->toChangePassword = $toChangePassword
			?? new RerouteToSibling('change-password');
		$this->afterProcess = $afterProcess
			?? new RedirectToRoot('log-in');
		$this->resetCodeField = EnrolledUserField::RESET_CODE->value;
	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws RecordNotFound
	 * @throws RecordRetrievalException
	 * @throws RandomException
	 */
	public function handleExchange(Exchange $exchange): void
	{
		try {
			$record = $this->findRecord($exchange);
			$record->setValue($this->resetCodeField, bin2hex(random_bytes(32)));
			$record->save();
			if($this->sendEmail($record, $exchange))
				$exchange->getSession()
					->addErrorMessage('Password Reset Link Sent')
					->addErrorCode(ResponseCode::OK);
			else
				$exchange->getSession()
					->addErrorMessage('User Has No Email Address')
					->addErrorCode(ResponseCode::PRECONDITION_FAILED);
		} catch (RecordNotFound $e) {
			$exchange->getSession()
				->addErrorMessage('User Not Found')
				->addErrorCode(ResponseCode::NOT_FOUND);
		}
		$exchange->redirect($this->afterProcess);
	}

	/**
	 * @param Exchange $exchange
	 * @return Record
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws RecordNotFound
	 * @throws RecordRetrievalException
	 */
	private function findRecord(Exchange $exchange):Record
	{
		$records		= $this->userRecords;
		$fieldName 		= EnrolledUserField::USERNAME->value;
		$username 		= $exchange->getParameters()->get($fieldName);
		return $records->findUniqueRecord($fieldName, $username);
	}

	/**
	 * @param Record $userRecord
	 * @param Exchange $exchange
	 * @return bool
	 * @throws EmailException
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws StreamSocketException
	 */
	private function sendEmail(Record $userRecord, Exchange $exchange):bool
	{
		$profile   = new UserProfile()->setRecord($userRecord);
		$resetCode = $userRecord->getValue($this->resetCodeField);
		if(!$profile->hasEmailAddress()) return false;
		$location = new Location()->setPath($exchange->getPath());
		$location->getParameters()->add($this->resetCodeField, $resetCode);
		$this->toChangePassword->reroute($location->getPath());
		$uri = new UriFromLocation($location);
		$siteAddress = $exchange->getSiteAddress();
		new EmailDelivery($exchange->getSmtpServer())
			->setRecipient($profile->getEmailAddress())
			->setRecipientName($profile->getFullName())
			->setSender($this->sender)
			->setSenderName($this->senderName)
			->setSubject('Reset Password Request')
			->setMessage("Reset your password here: $siteAddress$uri")
			->send()
		;
		return true;
	}
}