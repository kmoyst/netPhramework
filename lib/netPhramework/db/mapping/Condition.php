<?php

namespace netPhramework\db\mapping;

class Condition implements DataItem
{
	private Field $field;
	private ?string $value = null;
	private Operator $operator = Operator::EQUAL;

	public function getField(): Field
	{
		return $this->field;
	}

	public function setField(Field $field): self
	{
		$this->field = $field;
		return $this;
	}

	public function getValue(): ?string
	{
		return $this->value;
	}

	public function setValue(?string $value): self
	{
		$this->value = $value;
		return $this;
	}

	public function getOperator(): Operator
	{
		return $this->operator;
	}

	public function setOperator(Operator $operator): self
	{
		$this->operator = $operator;
		return $this;
	}
}