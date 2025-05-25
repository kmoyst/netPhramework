<?php

namespace netPhramework\rendering;

interface ConfigurableViewable
{
	public function setTemplateName(string $templateName):self;
	public function add(
		string $key, Encodable|string|iterable|null $value):self;
}