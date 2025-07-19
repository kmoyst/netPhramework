<?php

namespace netPhramework\exchange\cli;

use netPhramework\common\Variables;
use netPhramework\exchange\host\HostVariables;
use netPhramework\exchange\host\HostKey;

class CliHostVariables implements HostVariables
{
	private(set) string $protocol = 'cli';

	private(set) string $domain {get{
		return $this->get(HostKey::HOST_DOMAIN->value);
	}}

	public function __construct
	(
		public Variables $variables = new Variables()
	)
	{}

	public function get(string $key): ?string
	{
		return $this->variables->getOrNull($key);
	}
}