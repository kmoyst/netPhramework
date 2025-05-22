<?php

namespace netPhramework\db\authentication;

use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\db\configuration\RecordFinder;
use netPhramework\dispatching\DispatchToSibling;

class SaveProfile extends Leaf
{
	public function __construct(
		private readonly RecordFinder $userRecords,
		string $name = 'save-profile') { parent::__construct($name); }

	public function handleExchange(Exchange $exchange): void
	{
		$user   = $exchange->getSession()->getUser();
		$record = $this->userRecords
			->findUniqueRecord(
				EnrolledUserField::USERNAME->value, $user->getUsername());
		new UserProfile()
			->setRecord($record)
			->parseForValues($exchange->getParameters())
			->save()
		;
		$exchange->callback(new DispatchToSibling('view-profile'));
	}
}