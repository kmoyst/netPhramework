<?php

namespace netPhramework\rendering;

readonly class ReadableView implements Viewable
{
	public function __construct(
		private string $templateName,
		private iterable $variables
	) {}

	public function getTemplateName(): string
	{
		return $this->templateName;
	}

	public function getVariables(): iterable
	{
		return $this->variables;
	}
}