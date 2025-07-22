<?php

namespace netPhramework\rendering;

class ImmutableView extends EncodableViewable
{
	public function __construct(
		private(set) readonly string $templateName,
		private(set) readonly iterable $variables) {}

	public function getTemplateName(): string
	{
		return $this->templateName;
	}

	public function getVariables(): iterable
	{
		return $this->variables;
	}
}