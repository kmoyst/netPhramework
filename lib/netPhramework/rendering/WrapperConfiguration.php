<?php

namespace netPhramework\rendering;

interface WrapperConfiguration
{
	public function setTitlePrefix(string $titlePrefix): self;
	public function setTemplateName(string $templateName): self;
}