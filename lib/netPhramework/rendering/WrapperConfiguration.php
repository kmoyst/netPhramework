<?php

namespace netPhramework\rendering;

interface WrapperConfiguration
{
	public function setTitlePrefix(string $titlePrefix): self;
	public function setTemplateName(string $templateName): self;

	/**
	 * Allows addition of custom variables for wrapper.
	 *
	 * @param string $key
	 * @param string|Encodable|iterable|null $value
	 * @return self
	 */
	public function add(string $key,string|Encodable|iterable|null $value):self;

	/**
	 * @param string $templateName
	 * @return self
	 */
	public function addStyleSheet(string $templateName):self;
}