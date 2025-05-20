<?php

namespace netPhramework\core;

readonly class RequestInput
{
	public function getUri(): string
	{
		return filter_input(INPUT_SERVER, 'REQUEST_URI');
	}

	public function getQueryParameters(): ?array
	{
		return filter_input_array(INPUT_GET);
	}

	public function getPostParameters(): ?array
	{
		return filter_input_array(INPUT_POST);
	}
}