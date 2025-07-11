<?php

namespace netPhramework\db\authentication\profile;

use netPhramework\db\authentication\UserManager;
use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidSession;
use netPhramework\exchange\Exchange;
use netPhramework\locating\redirectors\RedirectToSibling;
use netPhramework\resources\Leaf;

class SaveProfile extends Leaf
{
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
		$user = $this->manager->findByUsername($exchange->session);
		$user->getProfile()->parse($exchange->parameters)->save();
		$exchange->redirect(new RedirectToSibling('view-profile'));
	}
}