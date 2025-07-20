<?php

namespace netPhramework\data\presentation\recordTable\columnSet;


use netPhramework\rendering\Viewable;

class ColumnHeader extends Viewable
{
	public function __construct(
		private(set) readonly string $name,
		private(set) readonly string $text,
		private(set) readonly int $width) {}

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