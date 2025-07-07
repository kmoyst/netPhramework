<?php

namespace netPhramework\db\authentication\nodes\passwordManagement;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\authentication\User;
use netPhramework\db\authentication\UserManager;
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
	private readonly Rerouter $toChangePassword;
	private readonly Redirector $afterProcess;

	public function __construct(
		private readonly UserManager $userManager,
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
			$parameters  = $exchange->getParameters();
			$user = $this->userManager->findByUsername($parameters);
			$user->newResetCode()->save();
			if($this->sendEmail($user, $exchange))
				$exchange->getSession()
					->addErrorMessage('Password Reset Link Sent')
					->addErrorCode(ResponseCode::OK);
			else
				$exchange->getSession()
					->addErrorMessage('User Has No Email Address')
					->addErrorCode(ResponseCode::PRECONDITION_FAILED);
		} catch (RecordNotFound) {
			$exchange->getSession()
				->addErrorMessage('User Not Found')
				->addErrorCode(ResponseCode::NOT_FOUND);
		}
		$exchange->redirect($this->afterProcess);
	}

	/**
	 * @param User $user
	 * @param Exchange $exchange
	 * @return bool
	 * @throws EmailException
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws StreamSocketException
	 */
	private function sendEmail(User $user, Exchange $exchange):bool
	{
		$resetCode = $user->getResetCode();
		if(!$user->hasEmailAddress()) return false;
		$location = new Location()->setPath($exchange->getPath());
		$location->getParameters()->add($user->getResetCodeField(), $resetCode);
		$this->toChangePassword->reroute($location->getPath());
		$uri = new UriFromLocation($location);
		$siteAddress = $exchange->getSiteAddress();
		new EmailDelivery($exchange->getSmtpServer())
			->setRecipient($user->getEmailAddress())
			->setRecipientName($user->getFullName())
			->setSender($this->sender)
			->setSenderName($this->senderName)
			->setSubject('Reset Password Request')
			->setMessage("Reset your password here: $siteAddress$uri")
			->send()
		;
		return true;
	}
}