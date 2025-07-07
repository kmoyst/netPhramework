<?php

namespace netPhramework\db\authentication\nodes\profileManagement;

use netPhramework\core\Exchange;
use netPhramework\core\LeafTrait;
use netPhramework\core\Node;
use netPhramework\db\authentication\UserManager;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\exceptions\AuthenticationException;
use netPhramework\exceptions\InvalidSession;
use netPhramework\exceptions\NotFound;
use netPhramework\presentation\InputSet;
use netPhramework\rendering\View;

class EditProfile implements Node
{
	use LeafTrait;

	public function __construct(
		private readonly UserManager $manager,
		string $name = 'edit-profile')
	{
		$this->name = $name;
	}

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
		$manager  = $this->manager;
		$username = $exchange->getSession()->getUser()->getUsername();
		$user     = $manager->findByUsername($username);
		$inputs   = new InputSet();
		$inputs
			->textInput($user->fields->firstName)
			->setValue($user->getFirstName())
		;
		$inputs
			->textInput($user->fields->lastName)
			->setValue($user->getLastName())
		;
		$inputs
			->textInput($user->fields->email)
			->setValue($user->getEmailAddress())
		;
		$exchange->ok(new View('edit-profile')
			->add('inputs', $inputs)
			->add('userDescription', $user->getUsername())
			->add('role', $user->getRole()->friendlyName())
		);
	}
}