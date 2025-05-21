<?php

namespace netPhramework\db\mapping;

use netPhramework\db\validators\Validator;

final class Field
{
    private string $name;
    private FieldType $type;
    private bool $allowsNull;
	private bool $mustBeUnique;
    private int $maxLength;
	/**
	 * @var Validator[]
	 */
	private array $validators = [];

	/**
	 * These validators are for database level only. For UI level validation
	 * extend the Save process, or make a new process.
	 *
	 * @param Validator $validator
	 * @return $this
	 */
	public function addValidator(Validator $validator):Field
	{
		$this->validators[] = $validator;
		return $this;
	}

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setType(FieldType $type): void
    {
        $this->type = $type;
    }

    public function setAllowsNull(bool $allowsNull): void
    {
        $this->allowsNull = $allowsNull;
    }

	public function setMustBeUnique(bool $mustBeUnique): void
	{
		$this->mustBeUnique = $mustBeUnique;
	}

	public function mustBeUnique(): bool
	{
		return $this->mustBeUnique;
	}

    public function getMaxLength(): int
    {
        return $this->maxLength;
    }

    public function setMaxLength(int $maxLength): void
    {
        $this->maxLength = $maxLength;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): FieldType
    {
        return $this->type;
    }

    public function allowsNull(): bool
    {
        return $this->allowsNull;
    }

	public function validate(?string $value):bool
	{
		foreach ($this->validators as $validator)
			if(!$validator->validate($value)) return false;
		return true;
	}
}