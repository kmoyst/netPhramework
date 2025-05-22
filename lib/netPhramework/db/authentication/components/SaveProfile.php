<?php

namespace netPhramework\db\authentication\components;

use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\db\authentication\EnrolledUserField;
use netPhramework\db\authentication\UserProfile;
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
		$exchange->redirect(new DispatchToSibling('view-profile'));
	}
}