<?php

namespace netPhramework\http;

class HttpInput
{
	private(set) string $uri {get{
		return filter_input(INPUT_SERVER, 'REQUEST_URI');
	}set{}}

	private(set) ?array $postParameters {get{
		return filter_input_array(INPUT_POST);
	}set{}}

	private(set) ?array $getParameters {get{
		return filter_input_array(INPUT_GET);
	}set{}}

	public function hasPostParameters():bool
	{
		return $this->postParameters !== null;
	}
}