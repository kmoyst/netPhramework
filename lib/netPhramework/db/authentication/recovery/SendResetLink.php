<?php

namespace netPhramework\db\authentication\recovery;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\authentication\UserManager;
use netPhramework\db\authentication\UserProfile;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
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
	protected string $name = 'send-link';

	public function __construct
	(
	private readonly UserManager $userManager,
	private readonly string $sender,
	private readonly ?string $senderName = null,
	private readonly Rerouter
		$toChangePassword = new RerouteToSibling('change-password'),
	private readonly Redirector
		$afterProcess = new RedirectToRoot('log-in')
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
			$parameters  = $exchange->getParameters();
			$user = $this->userManager->findByUsername($parameters);
			if($user === null) throw new RecordNotFound();
			$user->getProfile()->newResetCode()->save();
			if($this->sendEmail($user->getProfile(), $exchange))
				$exchange->getSession()
					->addFeedbackMessage('Password Reset Link Sent')
					->setFeedbackCode(ResponseCode::OK);
			else
				$exchange->getSession()
					->addFeedbackMessage('User Has No Email Address')
					->setFeedbackCode(ResponseCode::PRECONDITION_FAILED);
		} catch (RecordNotFound) {
			$exchange->getSession()
				->addFeedbackMessage('User Not Found')
				->setFeedbackCode(ResponseCode::NOT_FOUND);
		}
		$exchange->redirect($this->afterProcess);
	}

	/**
	 * @param UserProfile $profile
	 * @param Exchange $exchange
	 * @return bool
	 * @throws EmailException
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws StreamSocketException
	 */
	private function sendEmail(UserProfile $profile, Exchange $exchange):bool
	{
		if(!$profile->hasEmailAddress()) return false;
		$resetCode = $profile->getResetCode();
		$location = new Location()->setPath($exchange->getPath());
		$this->toChangePassword->reroute($location->getPath());
		$location->getParameters()->add($profile->fields->resetCode,$resetCode);
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