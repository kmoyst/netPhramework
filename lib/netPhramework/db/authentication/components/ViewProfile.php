<?php

namespace netPhramework\db\authentication\components;

use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\db\authentication\EnrolledUserField;
use netPhramework\db\authentication\UserProfile;
use netPhramework\db\configuration\RecordFinder;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\exceptions\AuthenticationException;
use netPhramework\rendering\View;

class ViewProfile extends Leaf
{
	public function __construct(
		private readonly RecordFinder $userRecords,
		string $name = 'view-profile') { parent::__construct($name); }

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
		$user 	= $exchange->getSession()->getUser();
		$record = $this->userRecords
			->findUniqueRecord(
				EnrolledUserField::USERNAME->value,
				$user->getUsername());
		$profile = new UserProfile()->setRecord($record);
		$callbackInput = $exchange->callbackFormInput();
		$exchange->ok(new View('view-profile')
			->addVariable('firstName', $profile->getFirstName())
			->addVariable('lastName', $profile->getLastName())
			->addVariable('username', $profile->getUsername())
			->addVariable('role', $profile->getRole()->friendlyName())
			->addVariable('emailAddress', $profile->getEmailAddress())
			->addVariable('callbackInput', $callbackInput)
		);
	}
}