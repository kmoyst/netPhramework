<?php

namespace netPhramework\db\presentation\recordTable;


use netPhramework\rendering\Viewable;

class ColumnHeader extends Viewable
{
	public function __construct(
		private readonly string $name,
		private readonly string $text,
		private readonly int $width) {}

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