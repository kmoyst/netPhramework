<?php

namespace netPhramework\db\mysql\mysqli;

class BindArgs
{
	private string $types = '';
	private array $data = [];

	public function addType(string $type):BindArgs
	{
		$this->types.=$type;
		return $this;
	}

	public function addQueryValue(?string $value):BindArgs
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