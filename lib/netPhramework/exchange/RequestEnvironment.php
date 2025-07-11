<?php

namespace netPhramework\exchange;

interface RequestEnvironment extends InterpreterEnvironment, ExchangeEnvironment
{
	public string $uri {get;}
}