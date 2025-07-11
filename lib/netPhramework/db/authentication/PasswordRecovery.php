<?php

namespace netPhramework\db\authentication;

use netPhramework\common\Variables;
use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidPassword;
use Random\RandomException;

class PasswordRecovery
{
	private ?User $user;

	public function __construct
	(
		private readonly UserManager $manager,
		private readonly Variables $parameters
	)
	{}

	public function getUser(): ?User
	{
		return $this->user;
	}

	public function setUser(?User $user): self
	{
		$this->user = $user;
		return $this;
	}

	/**
	 * @return self
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws RecordRetrievalException
	 */
	public function findUser():self
	{
		$field = $this->manager->fields->resetCode;
		$code  = $this->parameters->getOrNull($field);
		if($code !== null)
			$this->user = $this->manager->findUser($field, $code);
		return $this;
	}

	public function userFound():bool
	{
		return $this->user instanceof User;
	}

	public function getResetField():string
	{
		return $this->manager->fields->resetCode;
	}

	public function getPasswordField():string
	{
		return $this->manager->fields->password;
	}

	/**
	 * @return string|null
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function getResetCode():?string
	{
		return $this->user->record->getValue($this->getResetField());
	}

	/**
	 * @return self
	 * @throws FieldAbsent
	 * @throws InvalidValue
	 * @throws MappingException
	 * @throws RandomException
	 */
	public function newResetCode():self
	{
		$field = $this->user->fields->resetCode;
		$code = bin2hex(random_bytes(32));
		$this->user->record->setValue($field, $code);
		return $this;
	}

	/**
	 * @return $this
	 * @throws FieldAbsent
	 * @throws InvalidValue
	 * @throws MappingException
	 */
	public function clearResetCode():self
	{
		$this->user->record->setValue($this->user->fields->resetCode, null);
		return $this;
	}

	/**
	 * @return $this
	 * @throws FieldAbsent
	 * @throws InvalidValue
	 * @throws MappingException
	 * @throws Exception
	 * @throws InvalidPassword
	 */
	public function parsePassword():self
	{
		$password = $this->parameters->get($this->user->fields->password);
		$this->user->setPassword($password);
		return $this;
	}

	/**
	 * @return $this
	 * @throws InvalidValue
	 * @throws MappingException
	 * @throws DuplicateEntryException
	 */
	public function save():self
	{
		$this->user->save();
		return $this;
	}
}