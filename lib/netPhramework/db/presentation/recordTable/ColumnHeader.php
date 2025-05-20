<?php

namespace netPhramework\db\presentation\recordTable;


use netPhramework\rendering\Viewable;

readonly class ColumnHeader implements Viewable
{
	public function __construct(
		private string $name,
		private string $text,
		private int $width) {}

	public function getTemplateName(): string
	{
		return 'column-header';
	}

	public function getVariables(): iterable
	{
		return [
			'text' => $this->text,
			'width' => $this->width,
			'className' => $this->name
		];
	}
}