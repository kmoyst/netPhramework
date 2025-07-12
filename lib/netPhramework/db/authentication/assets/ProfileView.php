<?php

namespace netPhramework\db\authentication\assets;

use netPhramework\authentication\SessionUser;
use netPhramework\db\authentication\presentation\ProfileViewManager;
use netPhramework\db\authentication\User;
use netPhramework\db\authentication\UserManager;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\exceptions\AuthenticationException;
use netPhramework\exceptions\InvalidSession;
use netPhramework\exceptions\NotFound;
use netPhramework\exchange\Exchange;
use netPhramework\presentation\CallbackInput;
use netPhramework\presentation\FeedbackView;
use netPhramework\resources\Leaf;
use netPhramework\routing\ReroutedPath;
use netPhramework\routing\rerouters\Rerouter;

class ProfileView extends Leaf
{
	private Rerouter $toEditProfile;
	private UserManager $manager;

	public function __construct() {}

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
		$session 	 = $exchange->session;
		$user   	 = $this->findUser($session->user);
		$profile	 = $user->getProfile();
		$formAction  = new ReroutedPath($exchange->path, $this->toEditProfile);
		$viewManager = new ProfileViewManager($user)
			->mandatoryAdd('username',    $user->fields->username)
			->optionalAdd('firstName',    $profile->fields->firstName)
			->optionalAdd('lastName',     $profile->fields->lastName)
			->optionalAdd('emailAddress', $profile->fields->email)
			->addCustom('formAction',     $formAction)
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

	public function setToEditProfile(Rerouter $toEditProfile): self
	{
		$this->toEditProfile = $toEditProfile;
		return $this;
	}

	public function setUserManager(UserManager $manager): self
	{
		$this->manager = $manager;
		return $this;
	}
}