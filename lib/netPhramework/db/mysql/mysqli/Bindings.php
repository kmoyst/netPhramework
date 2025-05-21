<?php

namespace netPhramework\db\mysql\mysqli;

class Bindings
{
	private string $types = '';
	private array $data = [];

	public function addType(string $type):Bindings
	{
		$this->types.=$type;
		return $this;
	}

	public function addQueryValue(?string $value):Bindings
	{
		$this->data[] = $value;
		return $this;
	}

	public function getTypes(): string
	{
		return $this->types;
	}

	public function getData(): array
	{
		return $this->data;
	}
}