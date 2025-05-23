<?php

namespace netPhramework\db\authentication\processes;

use netPhramework\core\Exchange;
use netPhramework\db\authentication\EnrolledUser;
use netPhramework\db\core\Record;
use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\processes\Save;
use netPhramework\dispatching\dispatchers\Dispatcher;
use netPhramework\dispatching\dispatchers\DispatchToSibling;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidPassword;

class UserSave extends Save
{
	public function __construct(
		?Dispatcher $onSuccess = null,
		?string $name = null,
		private readonly ?EnrolledUser $enrolledUser = null)
	{
		parent::__construct($onSuccess, $name);
	}

	/**
	 * @param Exchange $exchange
	 * @param Record $record
	 * @return void
	 * @throws FieldAbsent
	 * @throws Exception
	 * @throws MappingException
	 */
	public function handleExchange(Exchange $exchange, Record $record): void
	{
		$enrolledUser = $this->enrolledUser ?? new EnrolledUser();
		$enrolledUser->setRecord($record);
		try {
            $enrolledUser->parseAndSet($exchange->getParameters());
            $enrolledUser->save();
            $exchange->getSession()->login($enrolledUser);
            $exchange->redirect($this->onSuccess);
        } catch (DuplicateEntryException) {
            $message = "User already exists: " . $enrolledUser->getUsername();
            $exchange->error(new Exception($message),
                new DispatchToSibling('sign-up'));
        } catch (InvalidValue|InvalidPassword $e) {
            $exchange->error($e, new DispatchToSibling('sign-up'));
        }
	}
}