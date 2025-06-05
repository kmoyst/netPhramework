<?php

namespace netPhramework\db\authentication\nodes;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\authentication\EnrolledUser;
use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\nodes\Save;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\locating\redirectors\RedirectToSibling;
use netPhramework\exceptions\InvalidPassword;

class UserSave extends Save
{
	public function __construct(
		?Redirector $onSuccess = null,
		?string $name = 'save',
		private readonly ?EnrolledUser $enrolledUser = null)
	{
		parent::__construct($onSuccess, $name);
	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws FieldAbsent
	 * @throws Exception
	 * @throws MappingException
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$enrolledUser = $this->enrolledUser ?? new EnrolledUser();
		$enrolledUser->setRecord($this->record);
		try {
            $enrolledUser->parseAndSet($exchange->getParameters());
            $enrolledUser->save();
            $exchange->getSession()->login($enrolledUser);
            $exchange->redirect($this->onSuccess);
        } catch (DuplicateEntryException) {
            $message = "User already exists: " . $enrolledUser->getUsername();
            $exchange->error(new Exception($message),
                new RedirectToSibling('sign-up'));
        } catch (InvalidValue|InvalidPassword $e) {
            $exchange->error($e, new RedirectToSibling('sign-up'));
        }
	}
}