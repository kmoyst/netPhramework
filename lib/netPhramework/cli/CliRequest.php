<?php

namespace netPhramework\cli;

use netPhramework\common\Variables;
use netPhramework\exchange\Request;
use netPhramework\routing\MutablePath;

class CliRequest extends Request
{
	protected(set) MutablePath $path {get{
		if(!isset($this->path))
		{
			$this->path = new MutablePath();
			$this->path->append(new PathFromCli());
		}
		return $this->path;
	}}


	protected(set) Variables $parameters {get{
		if(!isset($this->parameters))
		{
			$this->parameters = new Variables();
		}
		return $this->parameters;
	}}

	public function isModificationRequest(): bool
	{
		$question = "Is this a modification request? [Y/n: default n] ";
		return readline($question) === 'Y';
	}
}