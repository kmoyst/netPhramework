<?php

namespace netPhramework\responding;

readonly class Responder
{
	public function __construct(
		private Relayer $displayer,
		private Relayer $redirector) {}

	public function getDisplayer(): Relayer
	{
		return $this->displayer;
	}

	public function getRedirector(): Relayer
	{
		return $this->redirector;
	}
}