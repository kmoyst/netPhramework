<?php

namespace netPhramework\rendering;

interface WrapperSetup
{
	public function setTitlePrefix(string $titlePrefix): self;
	public function setTemplateName(string $templateName): self;
}