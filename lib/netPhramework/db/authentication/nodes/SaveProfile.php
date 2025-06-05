<?php

namespace netPhramework\db\authentication\nodes;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\core\LeafTrait;
use netPhramework\core\Node;
use netPhramework\db\authentication\EnrolledUserField;
use netPhramework\db\authentication\UserProfile;
use netPhramework\db\configuration\RecordFinder;
use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\locating\redirectors\RedirectToSibling;
use netPhramework\exceptions\InvalidSession;

class SaveProfile implements Node
{
	use LeafTrait;

	public function __construct(
		private readonly RecordFinder $userRecords,
		?string $name = 'save-profile')
	{
		$this->name = $name;
	}

    /**
     * @param Exchange $exchange
     * @return void
     * @throws DuplicateEntryException
     * @throws FieldAbsent
     * @throws InvalidValue
     * @throws MappingException
     * @throws RecordNotFound
     * @throws RecordRetrievalException
     * @throws Exception
     * @throws InvalidSession
     */
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
		$exchange->redirect(new RedirectToSibling('view-profile'));
	}
}