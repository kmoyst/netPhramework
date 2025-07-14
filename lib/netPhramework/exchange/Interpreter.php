<?php

namespace netPhramework\exchange;

use netPhramework\core\Environment;

interface Interpreter
{
	public function interpret(Environment $environment):Request;
}