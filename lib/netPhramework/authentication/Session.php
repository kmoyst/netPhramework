<?php

namespace netPhramework\authentication;

class Session
{
	private array $sessionVars;
	private ?SessionUser $user;

	public function login(User $user):Session
	{
		$this->start();
		$this->user = SessionUser::fromUser($user);
		$this->user->updateVariables($this->sessionVars);
		return $this;
	}

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

	public function confirmLoggedIn():bool
	{
		$this->start();
		return $this->user !== null;
	}

	public function getUser(): ?SessionUser
	{
		$this->start();
		return $this->user;
	}

	private function start(): void
	{
		if (session_status() == PHP_SESSION_NONE)
		{
			session_start();
			session_regenerate_id(true);
			$this->sessionVars =& $_SESSION;
			$this->user = SessionUser::fromArray($this->sessionVars);
		}
	}
}