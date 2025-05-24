<?php

namespace netPhramework\responding;

readonly class Responder
{
	public function __construct(
		private Displayer $displayer,
		private Redirector $redirector) {}

	public function getDisplayer(): Relayer
	{
		return $this->displayer;
	}

	public function getRedirector(): Relayer
	{
		return $this->redirector;
	}
}