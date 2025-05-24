<?php

namespace netPhramework\core;

use netPhramework\rendering\Encodable;
use netPhramework\rendering\View;

class Page extends Leaf
{
    protected View $view;
    protected string $templateName;

	public function __construct(
        string $templateName,
		?string $name = null,
        ?string $title = null)
	{
		parent::__construct($name);
        $this->view = new View($templateName, $title);
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