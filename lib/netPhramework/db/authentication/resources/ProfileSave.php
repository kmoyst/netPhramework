<?php

namespace netPhramework\db\authentication\resources;

use netPhramework\db\authentication\UserManager;
use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\exceptions\Exception;
use netPhramework\exchange\Exchange;
use netPhramework\nodes\Resource;
use netPhramework\routing\redirectors\Redirector;

class ProfileSave extends Resource
{
	private Redirector $onSuccess;
	private Redirector $onFailure;
	private UserManager $manager;

    /**
     * @param Exchange $exchange
     * @return void
     * @throws FieldAbsent
     * @throws MappingException
     * @throws RecordNotFound
     * @throws RecordRetrievalException
     * @throws Exception
     */
	public function handleExchange(Exchange $exchange): void
	{
		$user = $this->manager->findByUsername($exchange->session);
		try {
			$user->profile->parse($exchange->parameters)->save();
			$exchange->redirect($this->onSuccess);
		} catch (DuplicateEntryException|InvalidValue $e) {
			$exchange->error($e, $this->onFailure);
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