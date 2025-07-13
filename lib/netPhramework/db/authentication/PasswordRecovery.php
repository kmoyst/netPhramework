<?php

namespace netPhramework\db\authentication;

use DateTime;
use netPhramework\common\Utils;
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
	private(set) ?string $resetCode {
		get{
			return $this->user->record->getValue($this->getResetField());
		}
		set{}
	}
	private(set) ?string $resetTime {
		get{
			return $this->user->record->getValue($this->getResetTimeField());
		}
		set{}
	}

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

	public function getResetTimeField():string
	{
		return $this->manager->fields->resetTime;
	}

	public function getPasswordField():string
	{
		return $this->manager->fields->password;
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
		$code = bin2hex(random_bytes(32));
		$time = Utils::mysqlDateTime(new DateTime());
		$this->user->record->setValue($this->user->fields->resetCode, $code);
		$this->user->record->setValue($this->user->fields->resetTime, $time);
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
		$this->user->record->setValue($this->user->fields->resetTime, null);
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
	public function parsePassword(bool $new = false):self
	{
		$password = $this->parameters->get($this->user->fields->password);
		$this->user->setPassword($password, true, $new);
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