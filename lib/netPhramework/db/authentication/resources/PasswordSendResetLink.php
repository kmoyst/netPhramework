<?php

namespace netPhramework\db\authentication\resources;

use netPhramework\db\authentication\PasswordRecovery as Recovery;
use netPhramework\db\authentication\UserManager;
use netPhramework\db\authentication\UserProfile;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\exceptions\Exception;
use netPhramework\exchange\Exchange;
use netPhramework\exchange\ResponseCode;
use netPhramework\nodes\Resource;
use netPhramework\routing\Location;
use netPhramework\routing\redirectors\Redirector;
use netPhramework\routing\rerouters\Rerouter;
use netPhramework\routing\UriFromLocation;
use netPhramework\transferring\EmailDelivery;
use netPhramework\transferring\EmailException;
use netPhramework\transferring\StreamSocketException;
use Random\RandomException;

class PasswordSendResetLink extends Resource
{
	private string $sender;
	private ?string $senderName = null;
	private Rerouter $toChangePass;
	private Redirector $afterProcess;
	private UserManager $manager;

	private string $defaultMessage {
		get{
			$message   = [];
			$message[] = 'Check your email.';
			$message[] = 'If your account exists and has an email address,';
			$message[] = 'a reset link has been sent';
			return implode(' ', $message);
		}
		set{}
	}

	public function __construct() {}

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
			$parameters = $exchange->parameters;
			$recovery = new Recovery($this->manager, $parameters);
			$user = $this->manager->findByUsername($parameters);
			if($user === null) throw new RecordNotFound();
			$recovery->setUser($user)->newResetCode()->save();
			$this->sendEmail($user->profile, $exchange, $recovery->resetCode);
			$exchange->session
				->addFeedbackMessage($this->defaultMessage)
				->setFeedbackCode(ResponseCode::OK);
		} catch (RecordNotFound) {
			usleep(random_int(10000,30000)); // to confuse attackers
			$exchange->session
				->addFeedbackMessage($this->defaultMessage)
				->setFeedbackCode(ResponseCode::OK);
		}
		$exchange->redirect($this->afterProcess);
	}

	/**
	 * @param UserProfile $profile
	 * @param Exchange $exchange
	 * @param string $resetCode
	 * @return bool
	 * @throws EmailException
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws StreamSocketException
	 */
	private function sendEmail(
		UserProfile $profile, Exchange $exchange, string $resetCode):bool
	{
		if(!$profile->hasEmailAddress()) return false;
		$location = new Location()->setPath($exchange->path);
		$this->toChangePass->reroute($location->path);
		$location->getParameters()->add($profile->fields->resetCode,$resetCode);
		$uri = new UriFromLocation($location);
		$siteAddress = $exchange->siteAddress;
		new EmailDelivery($exchange->smtpServer)
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

	public function setSender(string $sender): self
	{
		$this->sender = $sender;
		return $this;
	}

	public function setSenderName(?string $senderName): self
	{
		$this->senderName = $senderName;
		return $this;
	}

	public function setToChangePass(Rerouter $toChangePass): self
	{
		$this->toChangePass = $toChangePass;
		return $this;
	}

	public function setAfterProcess(Redirector $afterProcess): self
	{
		$this->afterProcess = $afterProcess;
		return $this;
	}

	public function setUserManager(UserManager $manager): self
	{
		$this->manager = $manager;
		return $this;
	}
}