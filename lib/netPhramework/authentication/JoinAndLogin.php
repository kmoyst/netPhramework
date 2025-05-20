<?php

namespace netPhramework\authentication;

use netPhramework\core\Exchange;
use netPhramework\db\authentication\UserRecord;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\core\RecordSet;
use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\db\exceptions\MappingException;
use netPhramework\dispatching\Dispatcher;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidPassword;

class JoinAndLogin extends RecordSetProcess
{
	public function __construct(
		private readonly Dispatcher $dispatcher,
		?string $name = null) { parent::__construct($name); }

	/**
	 * @param Exchange $exchange
	 * @param RecordSet $recordSet
	 * @return void
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws InvalidValue
	 * @throws MappingException
	 */
	public function execute(Exchange $exchange, RecordSet $recordSet): void
	{
		try {
			$args = $exchange->getParameters();
			$user = new UserRecord($recordSet->newRecord());
			$user->parseAndSet($args); // this will break if I change the db
			$user->save();
			$exchange->getSession()
				->login(SessionUser::fromArray($args->toArray()));
			$exchange->callback($this->dispatcher);
		} catch (DuplicateEntryException) {
			$exchange->error(new Exception("Username already exists"));
		} catch (InvalidPassword $e) {
			$exchange->error($e);
		}
	}
}