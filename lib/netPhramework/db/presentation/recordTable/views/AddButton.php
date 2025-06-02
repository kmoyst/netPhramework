<?php

namespace netPhramework\db\presentation\recordTable\views;

use netPhramework\common\Variables;
use netPhramework\locating\MutablePath;
use netPhramework\presentation\Input;
use netPhramework\rendering\Viewable;

class AddButton extends Viewable
{
	private MutablePath $compositePath;
	private Input  $callbackInput;

	public function setCompositePath(MutablePath $compositePath): self
	{
		$this->compositePath = $compositePath;
		return $this;
	}

	public function setCallbackInput(Input $callbackInput): self
	{
		$this->callbackInput = $callbackInput;
		return $this;
	}

	public function getTemplateName(): string
	{
		return 'add-button-form';
	}

	public function getVariables(): Variables
	{
		return new Variables()
			->add('callbackInput', $this->callbackInput)
			->add('action', $this->compositePath->append('add'))
			;

	}
}