<?php

namespace netPhramework\db\authentication\resources;

use netPhramework\db\authentication\UserManager;
use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\nodes\RecordSetProcess;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidPassword;
use netPhramework\exchange\Exchange;
use netPhramework\routing\redirectors\Redirector;
use netPhramework\routing\redirectors\RedirectToSibling;

class UserRegister extends RecordSetProcess
{
	private Redirector $onSuccess;
	private Redirector $onFailure;
	private UserManager $manager;

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
}