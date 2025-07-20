<?php

namespace netPhramework\data\mapping;
use netPhramework\data\exceptions\InvalidValue;

final class Cell implements DataItem
{
    public function __construct(
		private readonly Field $field,
		private ?string $value) {}

	public function getField(): Field
	{
		return $this->field;
	}

    public function getName():string
    {
        return $this->field->getName();
    }

	/**
	 * @param string|null $value
	 * @return $this
	 * @throws InvalidValue
	 */
	public function setValue(?string $value):Cell
	{
		if(!$this->field->validate($value))
			throw new InvalidValue(
				"Invalid value for ". $this->getName().": $value");
		$this->value = $value;
		return $this;
	}

    public function getValue(): ?string
    {
        return $this->value;
    }
}