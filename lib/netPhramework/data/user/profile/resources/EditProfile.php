<?php

namespace netPhramework\data\user\profile\resources;

use netPhramework\data\exceptions\FieldAbsent;
use netPhramework\data\exceptions\MappingException;
use netPhramework\data\exceptions\RecordRetrievalException;
use netPhramework\data\user\UserManager;
use netPhramework\exceptions\AuthenticationException;
use netPhramework\exceptions\NotFound;
use netPhramework\exchange\Exchange;
use netPhramework\nodes\Resource;
use netPhramework\presentation\InputSet;
use netPhramework\rendering\View;
use netPhramework\routing\PathReroute;
use netPhramework\routing\rerouters\Rerouter;

class EditProfile extends Resource
{
	private Rerouter $toSaveProfile;

	public function __construct(private readonly UserManager $manager) {}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws AuthenticationException
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws RecordRetrievalException
	 * @throws NotFound
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$user     	= $this->manager->findByUsername($exchange->session);
		$profile  	= $user->profile;
		$formAction = new PathReroute($exchange->path, $this->toSaveProfile);
		$inputs   	= new InputSet();
		$inputs
			->textInput($profile->fields->firstName)
			->setValue($profile->getFirstName())
		;
		$inputs
			->textInput($profile->fields->lastName)
			->setValue($profile->getLastName())
		;
		$inputs
			->textInput($profile->fields->email)
			->setValue($profile->getEmailAddress())
		;
		$exchange->ok(new View('edit-profile')
			->add('inputs', $inputs)
			->add('userDescription', $user->getUsername())
			->add('role', $user->getRole()->friendlyName())
			->add('formAction', $formAction)
		);
	}

	public function setToSaveProfile(Rerouter $toSaveProfile): self
	{
		$this->toSaveProfile = $toSaveProfile;
		return $this;
	}
}