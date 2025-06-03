<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\rendering\ConfigurableViewable;
use netPhramework\rendering\ViewableConfigurator;

class FormInputConfigurator implements ViewableConfigurator
{
	private int $index;

	public function __construct(
		private readonly string $parentKey,
		private ?string $templateName = null,
		private readonly string $parentKeyVarName = 'parentName',
		private readonly string $indexVarName = 'index') {}

	public function setIndex(int $index): self
	{
		$this->index = $index;
		return $this;
	}

	public function setTemplateName(string $templateName): self
	{
		$this->templateName = $templateName;
		return $this;
	}

	public function configureViewable(ConfigurableViewable $viewable): void
	{
		$viewable
			->setTemplateName($this->templateName)
			->add($this->parentKeyVarName, $this->parentKey)
			->add($this->indexVarName, $this->index);
	}
}