<?php

namespace netPhramework\rendering;

class ImmutableView extends Viewable
{
	public function __construct(
		private readonly string $templateName,
		private readonly iterable $variables) {}

	public function getTemplateName(): string
	{
		return $this->templateName;
	}

	public function getVariables(): iterable
	{
		return $this->variables;
	}
}