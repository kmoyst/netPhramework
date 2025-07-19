<?php

namespace netPhramework\exchange\host;

/**
 * Responsible for translating host context
 * info into string variables. Specific to protocol.
 */
interface HostVariables
{
	public string $protocol {get;}

	public string $domain {get;}

	public function get(string $key):?string;
}