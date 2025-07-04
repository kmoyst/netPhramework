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
use netPhramework\presentation\CallbackInput;
use netPhramework\rendering\View;

class ViewProfile implements Node
{
	use LeafTrait;

	public function __construct(
		private readonly RecordFinder $userRecords,
		string $name = 'view-profile')
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
		$user 	= $exchange->getSession()->getUser();
		$record = $this->userRecords
			->findUniqueRecord(
				EnrolledUserField::USERNAME->value,
				$user->getUsername());
		$profile = new UserProfile()->setRecord($record);
		$callbackInput = new CallbackInput($exchange);
		$exchange->ok(new View('view-profile')
			->add('firstName', $profile->getFirstName())
			->add('lastName', $profile->getLastName())
			->add('username', $profile->getUsername())
			->add('role', $profile->getRole()->friendlyName())
			->add('emailAddress', $profile->getEmailAddress())
			->add('callbackInput', $callbackInput)
		);
	}
}