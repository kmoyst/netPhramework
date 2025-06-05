<?php

namespace netPhramework\db\authentication\nodes;

use netPhramework\core\Exchange;
use netPhramework\core\LeafTrait;
use netPhramework\core\Node;
use netPhramework\db\authentication\EnrolledUserField;
use netPhramework\db\authentication\UserProfile;
use netPhramework\db\configuration\RecordFinder;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\exceptions\InvalidSession;
use netPhramework\presentation\InputSet;
use netPhramework\rendering\View;

class EditProfile implements Node
{
	use LeafTrait;

	public function __construct(
		private readonly RecordFinder $userRecords,
		?string $name = 'edit-profile')
	{
		$this->name = $name;
	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws RecordNotFound
	 * @throws RecordRetrievalException
	 * @throws InvalidSession
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
			->add('inputs', $inputs)
			->add('userDescription', $user->getUsername())
			->add('role',$user->getRole()->friendlyName())
		);
	}
}