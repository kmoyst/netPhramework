<?php

namespace netPhramework\exchange\host;

class HostContext
{
	public function __construct(protected HostVariables $info) {}



	public function getMode():HostMode
	{
		return $this->mode;
	}
}