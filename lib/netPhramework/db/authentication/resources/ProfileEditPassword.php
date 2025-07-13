<?php

namespace netPhramework\db\authentication\resources;

use netPhramework\exchange\Exchange;
use netPhramework\nodes\Resource;
use netPhramework\presentation\InputSet;
use netPhramework\rendering\View;
use netPhramework\routing\ReroutedPath;
use netPhramework\routing\rerouters\Rerouter;

class ProfileEditPassword extends Resource
{
	protected Rerouter $onSave;

	public function handleExchange(Exchange $exchange): void
	{
		$formAction = new ReroutedPath($exchange->path, $this->onSave);
		$inputSet   = new InputSet();
		$inputSet->passwordInput('current-password');
		$inputSet->passwordInput('new-password');
		$view = new View('profile-edit-password');
		$view->add('inputSet', $inputSet);
		$view->add('formAction', $formAction);
		$exchange->ok($view);
	}

	public function setOnSave(Rerouter $onSave): self
	{
		$this->onSave = $onSave;
		return $this;
	}
}