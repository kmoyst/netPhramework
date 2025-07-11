<?php

namespace netPhramework\db\authentication\profile;

use netPhramework\db\authentication\UserManager;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\exceptions\AuthenticationException;
use netPhramework\exceptions\InvalidSession;
use netPhramework\exceptions\NotFound;
use netPhramework\exchange\Exchange;
use netPhramework\presentation\InputSet;
use netPhramework\rendering\View;
use netPhramework\resources\Leaf;

class EditProfile extends Leaf
{
	public function __construct(private readonly UserManager $manager) {}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws AuthenticationException
	 * @throws FieldAbsent
	 * @throws InvalidSession
	 * @throws MappingException
	 * @throws RecordRetrievalException
	 * @throws NotFound
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$user     = $this->manager->findByUsername($exchange->session);
		$profile  = $user->getProfile();
		$inputs   = new InputSet();
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
		);
	}
}