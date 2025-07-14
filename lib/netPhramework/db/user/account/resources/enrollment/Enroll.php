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

class Enroll extends AssetProcess
{
	private Redirector $onSuccess;
	private Redirector $onFailure;
	private UserManager $manager;
	private ?EmailDelivery $notification;

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
			try {
				if (isset($this->notification))
					$this->notification // this assumes all necessary props set
						->setServer($exchange->smtpServer)
						->send();
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

	public function setNotification(?EmailDelivery $notification): self
	{
		$this->notification = $notification;
		return $this;
	}
}