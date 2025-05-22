<?php

namespace netPhramework\db\authentication;

use netPhramework\authentication\UserRole;
use netPhramework\common\Variables;
use netPhramework\db\core\Record;
use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\db\exceptions\MappingException;
use netPhramework\exceptions\Exception;
use netPhramework\presentation\FormInput\InputSet;

class UserProfile
{
	private Record $record;

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}

	/**
	 * @return string
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function getUsername():string
	{
		return $this->record->getValue(EnrolledUserField::USERNAME->value);
	}

	/**
	 * @return UserRole
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function getRole():UserRole
	{
		return UserRole::tryFrom(
			$this->record->getValue(EnrolledUserField::ROLE->value));
	}

	/**
	 * @return string|null
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function getEmailAddress(): ?string
	{
		return $this->record->getValue(UserProfileField::EMAIL->value);
	}

	/**
	 * @param InputSet $inputSet
	 * @return UserProfile
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function addInputs(InputSet $inputSet):UserProfile
	{
		$inputSet
			->textInput(UserProfileField::EMAIL->value)
			->setValue($this->getEmailAddress())
		;
		return $this;
	}

	/**
	 * Uses UserProfileField Enum to set values of Record. This ensures
	 * that only exposed fields are allowed modification
	 *
	 * @param Variables $vars
	 * @return UserProfile
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws InvalidValue
	 * @throws Exception
	 */
	public function parseForValues(Variables $vars):UserProfile
	{
		foreach(UserProfileField::cases() as $field)
			if($vars->has($field->value))
				$this->record->getCell($field->value)
					->setValue($vars->get($field->value));
		return $this;
	}

	/**
	 * @return $this
	 * @throws MappingException
	 * @throws DuplicateEntryException
	 */
	public function save():UserProfile
	{
		$this->record->save();
		return $this;
	}
}