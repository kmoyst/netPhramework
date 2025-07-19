<?php

namespace netPhramework\exchange\http;

class HttpHostInfo
{
	public string $protocol {
		get { return $this->get('HTTPS') === 'on' ? 'https' : 'http'; }
		set {}
	}

	public string $domain {
		get { return $this->get('HTTP_HOST'); }
		set {}
	}

	public function get(string $key):?string
	{
		return filter_input(INPUT_SERVER, $key);
	}
}