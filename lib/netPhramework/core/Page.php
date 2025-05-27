<?php

namespace netPhramework\core;

use netPhramework\rendering\Encodable;
use netPhramework\rendering\View;

class Page implements Component
{
	use Leaf;

    protected View $view;
    protected string $templateName;

	public function __construct(
        string $templateName,
		?string $name = null,
        ?string $title = null)
	{
        $this->view = new View($templateName, $title);
		$this->name = $name;
	}

	public function handleExchange(Exchange $exchange): void
	{
		$exchange->ok($this->view);
	}

	public function getName(): string
	{
		return $this->name ?? $this->view->getTemplateName();
	}

    public function add(string $key, string|Encodable|iterable|null $value):self
    {
        $this->view->add($key, $value);
        return $this;
    }

    public function setTitle(?string $title = null):self
    {
        $this->view->setTitle($title);
        return $this;
    }
}