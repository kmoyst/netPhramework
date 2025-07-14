<?php

namespace netPhramework\db\user\profile\resources;

use netPhramework\user\SessionUser;
use netPhramework\db\user\profile\ViewManager;
use netPhramework\db\user\User;
use netPhramework\db\user\UserManager;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordRetrievalException;
use netPhramework\exceptions\AuthenticationException;
use netPhramework\exceptions\InvalidSession;
use netPhramework\exceptions\NotFound;
use netPhramework\exchange\Exchange;
use netPhramework\nodes\Resource;
use netPhramework\presentation\CallbackInput;
use netPhramework\presentation\FeedbackView;
use netPhramework\rendering\View;
use netPhramework\routing\ReroutedPath;
use netPhramework\routing\rerouters\Rerouter;

class ViewProfile extends Resource
{
	private Rerouter $toEditProfile;
	private Rerouter $toEditPassword;
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
		$session 	  = $exchange->session;
		$user   	  = $this->findUser($session->user);
		$profile	  = $user->profile;
		$editProfile  = new ReroutedPath($exchange->path, $this->toEditProfile);
		$editPassword = new ReroutedPath($exchange->path, $this->toEditPassword)
		;
		$manager = new ViewManager(new View('view-profile'), $user,);
		$manager
			->add('username', $profile->fields->username)
			->add('firstName', $profile->fields->firstName)
			->add('lastName', $profile->fields->lastName)
			->add('emailAddress', $profile->fields->email)
			;
		$manager->view
			->add('editProfile', $editProfile)
			->add('editPassword', $editPassword)
			->add('role', $user->getRole()->friendlyName())
			->add('callbackInput', new CallbackInput($exchange))
			->add('feedbackView', new FeedbackView($session))
		;
		$exchange->display($manager->view, $session->resolveResponseCode());
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

	public function setToEditPassword(Rerouter $toEditPassword): self
	{
		$this->toEditPassword = $toEditPassword;
		return $this;
	}

	public function setUserManager(UserManager $manager): self
	{
		$this->manager = $manager;
		return $this;
	}
}