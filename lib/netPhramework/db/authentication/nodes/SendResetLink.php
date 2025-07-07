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
use netPhramework\locating\redirectors\Redirector;
use netPhramework\locating\redirectors\RedirectToRoot;
use netPhramework\networking\EmailDelivery;
use netPhramework\networking\EmailException;
use netPhramework\networking\SmtpServer;
use netPhramework\networking\StreamSocketException;
use netPhramework\responding\ResponseCode;
use Random\RandomException;

class SendResetLink extends RecordSetProcess
{
	private readonly SmtpServer $smtpServer;
	private readonly Redirector $afterProcess;

	public function __construct(
		private readonly string $changePasswordNode,
		private readonly RecordFinder $userRecords,
		private readonly string $sender,
		private readonly ?string $senderName = null,
		?Redirector $afterProcess = null,
		private readonly string $resetCodeField = 'reset-code',
		?SmtpServer $smtpServer = null
	)
	{
		$this->smtpServer = $smtpServer ?? new SmtpServer();
		$this->afterProcess = new RedirectToRoot('log-in');
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
			if($this->sendEmail($record))
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
		$enrolledUser 	= $this->enrolledUser ?? new EnrolledUser();
		$fieldName 		= EnrolledUserField::USERNAME->value;
		$username 		= $exchange->getParameters()->get($fieldName);
		return $records->findUniqueRecord($fieldName, $username);
	}

	/**
	 * @param Record $userRecord
	 * @return bool
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws EmailException
	 * @throws StreamSocketException
	 */
	private function sendEmail(Record $userRecord):bool
	{
		$profile   = new UserProfile()->setRecord($userRecord);
		$resetCode = $userRecord->getValue($this->resetCodeField);
		if(($emailAddress = $profile->getEmailAddress()) === null)
			return false;
		$message = [];
		$message[] = 'Reset your password here: ';
		$message[] = $this->changePasswordNode;
		$message[] = "?reset-code=$resetCode";
		new EmailDelivery($this->smtpServer)
			->setRecipient($profile->getEmailAddress())
			->setRecipientName($profile->getFullName())
			->setSender($this->sender)
			->setSenderName($this->senderName)
			->setSubject('Reset Password Request')
			->setMessage(implode('', $message))
			->send()
		;
		return true;
	}
}