<?php

namespace netPhramework\db\authentication\nodes;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\authentication\UserProfile;
use netPhramework\db\configuration\RecordFinder;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\rendering\View;

class ChangePassword extends RecordSetProcess
{
	public function __construct(
		private readonly RecordFinder $userRecordFinder,
		private readonly string $fieldName = 'reset-code'
	)
	{

	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws RecordNotFound
	 * @throws RecordRetrievalException
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$fieldName = $this->fieldName;
		$resetCode = $exchange->getParameters()->get($fieldName);
		$record    = $this->userRecordFinder
			->findUniqueRecord($fieldName, $resetCode);
		$profile   = new UserProfile()->setRecord($record);
		$view = new View('change-password')
			->add('username', $profile->getUsername())
			;
		$exchange->ok($view);
	}
}