<?php

namespace netPhramework\rendering;

use netPhramework\common\Utils;
use netPhramework\common\Variables;
use netPhramework\dispatching\Location;

class View implements Viewable, Wrappable
{
    private Variables $variables;

    public function __construct(
        private readonly string $templateName,
        private ?string $title = null)
    {
        $this->variables = new Variables();
    }

    public function getTemplateName(): string
    {
        return $this->templateName;
    }

	public function addVariable(string $key,
						string|Viewable|Encodable|
						Location|iterable|null $value):self
	{
		$this->getVariables()->add($key, $value);
		return $this;
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