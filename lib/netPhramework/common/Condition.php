<?php

namespace netPhramework\common;

class Condition
{
	private string $key;
	private string $value;
	private Operator $operator;

	public function getKey(): string
	{
		return $this->key;
	}

	public function setKey(string $key): void
	{
		$this->key = $key;
	}

	public function getValue(): string
	{
		return $this->value;
	}

	public function setValue(string $value): void
	{
		$this->value = $value;
	}

	public function getOperator(): Operator
	{
		return $this->operator;
	}

	public function setOperator(Operator $operator): void
	{
		$this->operator = $operator;
	}
}