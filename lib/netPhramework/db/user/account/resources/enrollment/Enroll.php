<?php

namespace netPhramework\db\user\account\resources\enrollment;

use netPhramework\db\user\UserManager;
use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\nodes\AssetProcess;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidPassword;
use netPhramework\exchange\Exchange;
use netPhramework\routing\redirectors\Redirector;
use netPhramework\routing\redirectors\RedirectToSibling;
use netPhramework\transferring\EmailDelivery;
use netPhramework\transferring\EmailException;
use netPhramework\transferring\EmailInfo;
use netPhramework\transferring\StreamSocketException;

class Enroll extends AssetProcess
{
	private Redirector $onSuccess;
	private Redirector $onFailure;
	private UserManager $manager;
	private EmailInfo $notificationInfo;

	public function __construct() {}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws FieldAbsent
	 * @throws Exception
	 * @throws MappingException
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$user = $this->manager->getUser($this->recordSet->newRecord());
		try {
            $user->parseRegistration($exchange->parameters);
            $user->save();
			$exchange->session->login($user);
            $exchange->redirect($this->onSuccess);
			if(isset($this->notificationInfo))
				try {
					$this->notify($exchange, $this->notificationInfo);
				} catch (Exception $e) {
					error_log($e->getMessage());
				}
		} catch (DuplicateEntryException) {
            $message = "User already exists: " . $user->getUsername();
            $exchange->error(new Exception($message),
                new RedirectToSibling('sign-up'));
        } catch (InvalidValue|InvalidPassword $e) {
            $exchange->error($e, new RedirectToSibling('sign-up'));
        }
	}

	/**
	 * @param Exchange $exchange
	 * @param EmailInfo $info
	 * @return void
	 * @throws Exception
	 * @throws EmailException
	 * @throws StreamSocketException
	 */
	private function notify(Exchange $exchange, EmailInfo $info):void
	{
		$defaultMsg = "A new user has enrolled at $exchange->siteAddress.";
		new EmailDelivery()
			->setServer($exchange->smtpServer)
			->setSender($info->sender ?? 'webmaster@moyst.ca')
			->setSenderName($info->senderName ?? 'Moyst.Ca Webmaster')
			->setRecipient($info->recipient ?? 'webmaster@moyst.ca')
			->setRecipientName($info->recipientName ?? 'Moyst.Ca Webmaster')
			->setSubject($info->subject ?? 'New User Enrolled')
			->setMessage($info->message ?? $defaultMsg)
			->send()
		;
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

	public function enableNotification(EmailInfo $info): self
	{
		$this->notificationInfo = $info;
		return $this;
	}
}