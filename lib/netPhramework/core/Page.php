<?php

namespace netPhramework\core;

use netPhramework\rendering\Encodable;
use netPhramework\rendering\View;

class Page extends Node
{
	use LeafTrait;

    protected View $view;

	public function __construct(
        protected string $templateName,
		protected ?string $name = null,
        ?string $title = null)
	{
        $this->view = new View($templateName, $title);
	}

	public function getName():string
	{
		return $this->resolveName($this->name ?? $this->templateName);
	}

	public function handleExchange(Exchange $exchange): void
	{
		$exchange->ok($this->view);
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