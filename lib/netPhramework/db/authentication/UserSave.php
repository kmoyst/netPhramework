<?php

namespace netPhramework\db\authentication;

use netPhramework\core\Exchange;
use netPhramework\db\core\Record;
use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\processes\Save;
use netPhramework\dispatching\DispatchToParent;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidPassword;

class UserSave extends Save
{
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
		try {
			$enrolledUser = new EnrolledUser($record); // could be new
			$enrolledUser->parseAndSet($exchange->getParameters());
			$enrolledUser->save();
			$exchange->callback($this->dispatcher ?? new DispatchToParent(''));
		} catch (DuplicateEntryException|InvalidValue|InvalidPassword $e) {
			$exchange->error($e);
		}
	}
}