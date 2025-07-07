<?php

namespace netPhramework\db\authentication;

use netPhramework\common\Variables;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Record;

readonly class UserProfile
{
	public function __construct(
		private Record $record,
		private UserFieldNames $fields
	)
	{

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
	 * @param Variables $vars
	 * @return $this
	 * @throws FieldAbsent
	 * @throws InvalidValue
	 * @throws MappingException
	 */
	public function parse(Variables $vars):self
	{
		$this->setFirstName($vars->getOrNull($this->fields->firstName));
		$this->setLastName($vars->getOrNull($this->fields->lastName));
		$this->setEmailAddress($vars->getOrNull($this->fields->email));
		return $this;
	}
}