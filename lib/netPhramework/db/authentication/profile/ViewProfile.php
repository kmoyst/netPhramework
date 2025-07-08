<?php

namespace netPhramework\db\authentication\profile;

use netPhramework\authentication\SessionUser;
use netPhramework\core\Exchange;
use netPhramework\core\LeafBehaviour;
use netPhramework\core\Node;
use netPhramework\db\authentication\presentation\ProfileViewManager;
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

class ViewProfile extends Node
{
	use LeafBehaviour;

	public function __construct(private readonly UserManager $manager) {}

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
		$profile	 = $user->getProfile()
		;
		$viewManager = new ProfileViewManager($user)
			->mandatoryAdd('username',    $user->fields->username)
			->optionalAdd('firstName',    $profile->fields->firstName)
			->optionalAdd('lastName',     $profile->fields->lastName)
			->optionalAdd('emailAddress', $profile->fields->email)
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
	 * @throws InvalidSession
	 * @throws MappingException
	 * @throws NotFound
	 * @throws RecordRetrievalException
	 */
	private function findUser(?SessionUser $sessionUser):User
	{
		if($sessionUser === null)
			throw new AuthenticationException(
				"A visitor tried to view their profile");
		return $this->manager->findByUsername($sessionUser->getUsername());
	}
}