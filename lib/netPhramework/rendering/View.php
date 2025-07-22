<?php

namespace netPhramework\rendering;

use netPhramework\common\Utils;
use netPhramework\common\Variables;
use Stringable;

class View implements
	Wrappable, ConfigurableView, Viewable, Encodable
{
    private readonly Variables $variables;

    public function __construct(
        private string $templateName,
        private ?string $title = null)
    {
        $this->variables = new Variables();
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

	public function setTemplateName(string $templateName): self
	{
		$this->templateName = $templateName;
		return $this;
	}

    public function getTitle(): string
    {
        return $this->title ?? Utils::kebabToSpace($this->templateName);
    }

	public function setTitle(?string $title): View
	{
		$this->title = $title;
		return $this;
	}

    public function getContent(): self
    {
        return $this;
    }

	public function getVariables(): Variables
	{
		return $this->variables;
	}

	public function encode(Encoder $encoder): Stringable|string
	{
		return $encoder->encodeViewable($this);
	}
}