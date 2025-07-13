<?php

namespace netPhramework\rendering;

use netPhramework\common\Variables;

class Message extends View implements Wrappable
{
	private ?string $title;

	public function __construct(private readonly string $message)
	{
		parent::__construct('message');
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

	public function getVariables(): Variables
	{
		return new Variables()
			->add('message', $this->message);
	}
}