<?php

namespace netPhramework\db\authentication\recovery;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\db\authentication\PasswordRecovery as Recovery;
use netPhramework\db\authentication\UserManager;
use netPhramework\db\authentication\UserProfile;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\locating\Location;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\locating\redirectors\RedirectToRoot as toRoot;
use netPhramework\locating\rerouters\Rerouter;
use netPhramework\locating\rerouters\RerouteToSibling as toSibling;
use netPhramework\locating\UriFromLocation;
use netPhramework\networking\EmailDelivery;
use netPhramework\networking\EmailException;
use netPhramework\networking\StreamSocketException;
use netPhramework\responding\ResponseCode;
use Random\RandomException;

class SendResetLink extends Leaf
{

	public function __construct
	(
	private readonly UserManager $manager,
	private readonly string $sender,
	private readonly ?string $senderName = null,
	private readonly Rerouter $toChangePass = new toSibling('change-password'),
	private readonly Redirector $afterProcess = new toRoot('log-in')
	)
	{}

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
			if($this->sendEmail(
				$user->getProfile(), $exchange, $recovery->getResetCode()))
				$exchange->session
					->addFeedbackMessage('Password Reset Link Sent')
					->setFeedbackCode(ResponseCode::OK);
			else
				$exchange->session
					->addFeedbackMessage('User Has No Email Address')
					->setFeedbackCode(ResponseCode::PRECONDITION_FAILED);
		} catch (RecordNotFound) {
			$exchange->session
				->addFeedbackMessage('User Not Found')
				->setFeedbackCode(ResponseCode::NOT_FOUND);
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
		$this->toChangePass->reroute($location->getPath());
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
}