<?php

namespace netPhramework\rendering;

use netPhramework\common\Utils;
use netPhramework\common\Variables;

class View implements Viewable, Wrappable
{
    private ?string $title;
    private Variables $variables;

    public function __construct(private readonly string $templateName)
    {
        $this->title = null;
        $this->variables = new Variables();
    }

    public function getTemplateName(): string
    {
        return $this->templateName;
    }

    public function getVariables(): Variables
    {
        return $this->variables;
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

    public function getContent(): Viewable
    {
        return $this;
    }
}