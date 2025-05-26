<?php

namespace netPhramework\rendering;

use netPhramework\common\Variables;

class Wrapper extends Viewable implements WrapperConfiguration
{
	private Variables $variables;
	private Wrappable $wrappable;
	private string $templateName = 'wrapper';
	private string $titlePrefix = '';

	public function __construct()
	{
		$this->variables = new Variables();
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
			;
    }
}