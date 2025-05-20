<?php

namespace netPhramework\presentation;

use netPhramework\common\Utils;
use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\rendering\Viewable;
use netPhramework\rendering\Wrappable;

class Page extends Leaf implements Viewable, Wrappable
{
	public function __construct(
		private readonly string $templateName,
		?string $name = null,
		private ?string $title = null)
	{
		parent::__construct($name);
	}

	public function handleExchange(Exchange $exchange): void
	{
		$exchange->ok($this);
	}

	public function setTitle(?string $title): Page
	{
		$this->title = $title;
		return $this;
	}

	public function getTemplateName(): string
	{
		return $this->templateName;
	}

	public function getVariables(): iterable
	{
		return [];
	}

	public function getTitle(): string
	{
		return $this->title ?? Utils::kebabToSpace($this->getName());
	}

	public function getContent(): Viewable
	{
		return $this;
	}

	public function getName(): string
	{
		return $this->name ?? $this->templateName;
	}
}