<?php

namespace netPhramework\data\user;

use netPhramework\common\Variables;
use netPhramework\data\core\Record;
use netPhramework\data\exceptions\DuplicateEntryException;
use netPhramework\data\exceptions\FieldAbsent;
use netPhramework\data\exceptions\InvalidValue;
use netPhramework\data\exceptions\MappingException;

readonly class UserProfile
{
	public function __construct(
		private Record $record,
		public UserFieldNames $fields
	) {}

	/**
	 * @param Variables $vars
	 * @return $this
	 * @throws FieldAbsent
	 * @throws InvalidValue
	 * @throws MappingException
	 */
	public function parse(Variables $vars):self
	{
		$this->setEmailAddress($vars->getOrNull($this->fields->email));
		$this->setFirstName($vars->getOrNull($this->fields->firstName));
		$this->setLastName($vars->getOrNull($this->fields->lastName));
		return $this;
	}

	/**
	 * @return string|null
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function getEmailAddress():?string
	{
		return $this->record->getValue($this->fields->email);
	}

	/**
	 * @return string|null
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function getFirstName():?string
	{
		return $this->record->getValue($this->fields->firstName);
	}

	/**
	 * @return string|null
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function getLastName():?string
	{
		return $this->record->getValue($this->fields->lastName);
	}

	/**
	 * @param ?string $emailAddress
	 * @return $this
	 * @throws FieldAbsent
	 * @throws InvalidValue
	 * @throws MappingException
	 */
	public function setEmailAddress(?string $emailAddress):self
	{
		$this->record->setValue($this->fields->email, $emailAddress);
		return $this;
	}

	/**
	 * @param ?string $firstName
	 * @return $this
	 * @throws FieldAbsent
	 * @throws InvalidValue
	 * @throws MappingException
	 */
	public function setFirstName(?string $firstName):self
	{
		$this->record->setValue($this->fields->firstName, $firstName);
		return $this;
	}

	/**
	 * @param ?string $lastName
	 * @return $this
	 * @throws FieldAbsent
	 * @throws InvalidValue
	 * @throws MappingException
	 */
	public function setLastName(?string $lastName):self
	{
		$this->record->setValue($this->fields->lastName, $lastName);
		return $this;
	}

	/**
	 * @return string|null
	 * @throws MappingException
	 */
	public function getFullName():?string
	{
		try {
			$f = $this->getFirstName();
			$l = $this->getLastName();
			if (empty($f) || empty($l)) return null;
			else return "$f $l";
		} catch (FieldAbsent) {
			return null;
		}
	}

	/**
	 * @return bool
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function hasEmailAddress():bool
	{
		return !empty($this->getEmailAddress());
	}

	/**
	 * @return $this
	 * @throws MappingException
	 * @throws DuplicateEntryException
	 */
	public function save():self
	{
		$this->record->save();
		return $this;
	}
}