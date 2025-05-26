<?php

namespace netPhramework\rendering;

use netPhramework\common\Variables;

class Wrapper extends Viewable implements WrapperConfiguration
{
	private Variables $variables;
	private Wrappable $wrappable;
	private array $styleSheets = [];

	public function __construct(
		private string $templateName = 'wrapper',
		private string $titlePrefix = '')
	{
		$this->variables = new Variables();
	}

	/** @inheritdoc  */
	public function addStyleSheet(string $templateName):self
	{
		array_push($this->styleSheets, new Template($templateName));
		return $this;
	}

	public function wrap(Wrappable $wrappable):Viewable
    {
		$this->wrappable = $wrappable;
        return $this;
    }

	public function setTitlePrefix(string $titlePrefix): self
	{
		$this->titlePrefix = $titlePrefix;
		return $this;
	}

	public function setTemplateName(string $templateName): self
	{
		$this->templateName = $templateName;
		return $this;
	}

    public function getTemplateName(): string
    {
        return $this->templateName;
    }

	/**@inheritdoc */
	public function add(string $key, string|Encodable|iterable|null $value):self
	{
		$this->variables->add($key, $value);
		return $this;
	}

    public function getVariables(): iterable
    {
		return $this->variables
			->add('content', $this->wrappable->getContent())
			->add('title',
				trim("$this->titlePrefix - ".$this->wrappable->getTitle(),'- '))
			->add('stylesheets', $this->styleSheets)
			;
    }
}