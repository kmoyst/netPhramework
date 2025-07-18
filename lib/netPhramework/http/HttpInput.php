<?php

namespace netPhramework\http;

class HttpInput
{
	public string $uri {get{
		return filter_input(INPUT_SERVER, 'REQUEST_URI');
	}}

	public ?array $postParameters {get{
		return filter_input_array(INPUT_POST);
	}}

	public ?array $getParameters {get{
		return filter_input_array(INPUT_GET);
	}}

	public function hasPostParameters():bool
	{
		return $this->postParameters !== null;
	}
}