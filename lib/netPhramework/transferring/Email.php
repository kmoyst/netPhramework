<?php

namespace netPhramework\transferring;

readonly class Email
{
	public function __construct(
		public string $from,
		public string $to,
		public string $subject,
		public string $message,
		public string $charset
	) {}
}