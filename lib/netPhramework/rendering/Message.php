<?php

namespace netPhramework\rendering;

class Message extends Viewable implements Wrappable
{
	private ?string $title;

	public function __construct(private readonly string $message) {}

	public function getTemplateName(): string
	{
		return 'message';
	}

	public function getTitle(): string
	{
		return $this->title ?? '';
	}

	public function setTitle(?string $title): Message
	{
		$this->title = $title;
		return $this;
	}

	public function getContent(): self
	{
		return $this;
	}

	public function getVariables(): iterable
	{
		return ['message' => $this->message];
	}
}