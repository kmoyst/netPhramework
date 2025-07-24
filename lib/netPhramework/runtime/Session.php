<?php

namespace netPhramework\runtime;

use netPhramework\exceptions\InvalidRoleInSession;
use netPhramework\exchange\ResponseCode;
use netPhramework\user\User;

class Session
{
    private string $feedbackMessageKey = 'feedbackMessage';
    private string $feedbackCodeKey    = 'feedbackCode';

	private SessionUserProvider $userProvider;
	private array $sessionVars;
	private(set) ?SessionUser $user;

	public function __construct(?SessionUserProvider $userProvider = null)
	{
		$this->userProvider = $userProvider ?? new SessionUserAggregate();
	}

    public function addFeedbackMessage(string $message):Session
    {
        $this->sessionVars[$this->feedbackMessageKey] = $message;
        return $this;
    }

    public function getFeedbackAndClear():?string
    {
        if(isset($this->sessionVars[$this->feedbackMessageKey]))
        {
            $message = $this->sessionVars[$this->feedbackMessageKey];
            unset($this->sessionVars[$this->feedbackMessageKey]);
            return $message;
        }
        else return null;
    }

    public function setFeedbackCode(ResponseCode $code):self
    {
        $this->sessionVars[$this->feedbackCodeKey] = $code->value;
        return $this;
    }

    public function resolveResponseCode():ResponseCode
    {
        if(isset($this->sessionVars[$this->feedbackCodeKey]))
        {
            $code = ResponseCode::tryFrom(
                $this->sessionVars[$this->feedbackCodeKey]) ?? ResponseCode::OK;
            unset($this->sessionVars[$this->feedbackCodeKey]);
            return $code;
        }
        else return ResponseCode::OK;
    }

	public function login(User $user):Session
	{
		$this->user = $this->userProvider->fromUser($user);
		$this->user->populateVariables($this->sessionVars);
		return $this;
	}

	public function logout():Session
	{
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
		return $this->user !== null;
	}

	/**
	 * @return self
	 * @throws InvalidRoleInSession
	 */
	public function start(): self
	{
		if (session_status() == PHP_SESSION_NONE)
		{
			session_start();
			session_regenerate_id(true);
			$this->sessionVars =& $_SESSION;
			try {
				$this->user = $this->userProvider->fromArray($this->sessionVars);
			} catch (InvalidRoleInSession $e) {
				$this->user = null;
				throw $e;
			}
		}
		return $this;
	}
}