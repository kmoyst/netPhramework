<?php

namespace netPhramework\db\user\profile\resources;

use netPhramework\db\user\UserManager;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidPassword;
use netPhramework\exceptions\NotFound;
use netPhramework\exchange\Exchange;
use netPhramework\exchange\ResponseCode;
use netPhramework\nodes\Resource;
use netPhramework\routing\redirectors\Redirector;

class SavePassword extends Resource
{
	private UserManager $userManager;
	private Redirector $onSuccess;
	private Redirector $onFailure;

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws Exception
	 */
	public function handleExchange(Exchange $exchange): void
	{
		try {
			$user = $exchange->session->user;
			if($user === null) throw new Exception("No user in session");
			$user = $this->userManager->findByUsername($user->getUsername());
			$args = $exchange->parameters;
			$currentPassword = $args->getOrNull('current-password');
			$newPassword	 = $args->getOrNull('new-password');
			if(!$currentPassword || !$newPassword)
				throw new InvalidPassword('Please fill in both inputs');
			if(!$user->checkPassword($currentPassword))
				throw new InvalidPassword('Current password incorrect');
			$user->setPassword($newPassword, true, true)->save();
			$exchange->session->login($user);
			$exchange->session->addFeedbackMessage('Password saved');
			$exchange->session->setFeedbackCode(ResponseCode::OK);
			$exchange->redirect($this->onSuccess);
		} catch (NotFound) {
			throw new Exception("Logged in user not found in database");
		} catch (InvalidPassword $e) {
			$exchange->error($e, $this->onFailure);
		}
	}

	public function setUserManager(UserManager $userManager): self
	{
		$this->userManager = $userManager;
		return $this;
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
}