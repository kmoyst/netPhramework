<?php

namespace netPhramework\authentication;

use netPhramework\exceptions\AuthenticationException;

class Session
{
	private SessionUserProvider $userProvider;
	private array $sessionVars;
	private ?SessionUser $user = null;

	public function __construct(?SessionUserProvider $userProvider = null)
	{
		$this->userProvider = $userProvider ?? new SessionUserAggregate();
	}

	/**
	 * @param User $user
	 * @return $this
	 * @throws AuthenticationException
	 */
	public function login(User $user):Session
	{
		$this->start();
		$this->user = $this->userProvider->fromUser($user);
		$this->user->populateVariables($this->sessionVars);
		return $this;
	}

	/**
	 * @return $this
	 * @throws AuthenticationException
	 */
	public function logout():Session
	{
		$this->start();
        $this->user = null;
		$this->sessionVars = [];
		if (session_status() === PHP_SESSION_ACTIVE) {
			session_destroy();
			if (ini_get("session.use_cookies")) {
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]);
			}
		}
		return $this;
	}

	/**
	 * @return bool
	 * @throws AuthenticationException
	 */
	public function confirmLoggedIn():bool
	{
		$this->start();
		return $this->user !== null;
	}

	/**
	 * @return User|null
	 * @throws AuthenticationException
	 */
	public function getUser(): ?User
	{
		$this->start();
		return $this->user;
	}

	/**
	 * @return void
	 * @throws AuthenticationException
	 */
	private function start(): void
	{
		if (session_status() == PHP_SESSION_NONE)
		{
			session_start();
			session_regenerate_id(true);
			$this->sessionVars =& $_SESSION;
			$this->user = $this->userProvider->fromArray($this->sessionVars);
		}
	}
}