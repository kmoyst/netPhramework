<?php

namespace netPhramework\db\authentication\processes;

use netPhramework\core\Exchange;
use netPhramework\db\authentication\EnrolledUser;
use netPhramework\db\core\Record;
use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\processes\Save;
use netPhramework\dispatching\Dispatcher;
use netPhramework\dispatching\DispatchToParent;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidPassword;

class UserSave extends Save
{
	public function __construct(
		?Dispatcher $dispatcher = null,
		?string $name = null,
		private readonly ?EnrolledUser $enrolledUser = null)
	{
		parent::__construct($dispatcher, $name);
	}

	/**
	 * @param Exchange $exchange
	 * @param Record $record
	 * @return void
	 * @throws FieldAbsent
	 * @throws Exception
	 * @throws MappingException
	 */
	public function execute(Exchange $exchange, Record $record): void
	{
		$enrolledUser = $this->enrolledUser ?? new EnrolledUser();
		$enrolledUser->setRecord($record);
		try {
			$enrolledUser->parseAndSet($exchange->getParameters());
		} catch (InvalidPassword $e) {
			$exchange->error($e);
			return;
		}
		try {
			$enrolledUser->save();
		} catch (DuplicateEntryException) {
			$exchange->error(
				new Exception(
					"User already exists: ". $enrolledUser->getUsername()));
			return;
		}
		$exchange->getSession()->login($enrolledUser);
		$exchange->callback($this->dispatcher ?? new DispatchToParent(''));
	}
}