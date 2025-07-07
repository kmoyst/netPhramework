<?php

namespace netPhramework\db\authentication\nodes\profileManagement;

use netPhramework\authentication\SessionUser;
use netPhramework\core\Exchange;
use netPhramework\core\LeafTrait;
use netPhramework\core\Node;
use netPhramework\db\authentication\nodes\profileManagement\presentation\ViewManager;
use netPhramework\db\authentication\User;
use netPhramework\db\authentication\UserManager;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\exceptions\AuthenticationException;
use netPhramework\exceptions\InvalidSession;
use netPhramework\exceptions\NotFound;
use netPhramework\presentation\CallbackInput;
use netPhramework\presentation\FeedbackView;

class ViewProfile implements Node
{
	use LeafTrait;

	public function __construct(
		private readonly UserManager $manager,
		string $name = 'view-profile')
	{
		$this->name = $name;
	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws AuthenticationException
	 * @throws FieldAbsent
	 * @throws InvalidSession
	 * @throws MappingException
	 * @throws NotFound
	 * @throws RecordRetrievalException
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$session 	 = $exchange->getSession();
		$user   	 = $this->findUser($session->getUser());
		$fields 	 = $user->getFields()
		;
		$viewManager = new ViewManager($user)
			->mandatoryAdd('username',    $fields->username)
			->optionalAdd('firstName',    $fields->firstName)
			->optionalAdd('lastName',     $fields->lastName)
			->optionalAdd('emailAddress', $fields->email)
			->addCustom('role',    		  $user->getRole()->friendlyName())
			->addCustom('callbackInput',  new CallbackInput($exchange))
			->addCustom('feedbackView',   new FeedbackView($session))
			;
		$responseCode = $session->resolveResponseCode();
		$exchange->display($viewManager->view, $responseCode);
	}

	/**
	 * @param ?SessionUser $sessionUser
	 * @return User
	 * @throws AuthenticationException
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws RecordRetrievalException
	 * @throws NotFound
	 */
	private function findUser(?SessionUser $sessionUser):User
	{
		if($sessionUser === null)
			throw new AuthenticationException(
				"A visitor tried to view their profile");
		return $this->manager->findByUsername($sessionUser->getUsername());
	}
}