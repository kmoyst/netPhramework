<?php

namespace netPhramework\db\authentication;

use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\db\configuration\RecordFinder;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\exceptions\AuthenticationException;
use netPhramework\presentation\FormInput\InputSet;
use netPhramework\rendering\View;

class EditProfile extends Leaf
{
	public function __construct(
		private readonly RecordFinder $userRecords,
		string $name = 'edit-profile') { parent::__construct($name); }

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws RecordNotFound
	 * @throws RecordRetrievalException
	 * @throws AuthenticationException
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$user 	 = $exchange->getSession()->getUser();
		$record  = $this->userRecords
			->findUniqueRecord(
				EnrolledUserField::USERNAME->value,
				$user->getUsername());
		$inputs  = new InputSet();
		$profile = new UserProfile();
		$profile->setRecord($record)->addInputs($inputs);
		$exchange->ok(new View('edit-profile')
			->addVariable('inputs', $inputs)
			->addVariable('userDescription', $user->getUsername())
			->addVariable('role',$user->getRole()->friendlyName())
		);
	}
}