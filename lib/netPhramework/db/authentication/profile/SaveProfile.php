<?php

namespace netPhramework\db\authentication\profile;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\core\LeafTrait;
use netPhramework\core\Node;
use netPhramework\db\authentication\UserManager;
use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\exceptions\InvalidSession;
use netPhramework\locating\redirectors\RedirectToSibling;

class SaveProfile implements Node
{
	use LeafTrait;

	public function __construct(private readonly UserManager $manager) {}

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
		$user = $this->manager->findByUsername($exchange->getSession());
		$user->getProfile()->parse($exchange->getParameters())->save();
		$exchange->redirect(new RedirectToSibling('view-profile'));
	}
}