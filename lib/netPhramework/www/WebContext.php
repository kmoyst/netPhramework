<?php

namespace netPhramework\www;

use netPhramework\core\RuntimeContext;

class WebContext implements RuntimeContext
{
	public WebRequestInput $requestInput {get{
		return new WebRequestInput();
	}}

	public function get(string $key):?string
	{
		return filter_input(INPUT_SERVER, $key);
	}
}