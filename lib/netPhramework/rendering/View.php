<?php

namespace netPhramework\rendering;

use netPhramework\common\Utils;
use netPhramework\common\Variables;
use netPhramework\dispatching\ReadableLocation;
use netPhramework\dispatching\ReadablePath;

class View extends Viewable implements Wrappable, ViewConfiguration
{
    private readonly Variables $variables;

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

	public function add(string $key,
						string|Viewable|Encodable|ReadablePath|
						ReadableLocation|iterable|null $value):View
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

    public function getContent(): self
    {
        return $this;
    }
}