<?php

namespace netPhramework\user;

use netPhramework\exceptions\InvalidSession;
use netPhramework\exchange\ResponseCode;

class Session
{
    private string $feedbackMessageKey = 'feedbackMessage';
    private string $feedbackCodeKey    = 'feedbackCode';

	private SessionUserProvider $userProvider;
	private array $sessionVars;
	private(set) ?SessionUser $user {
		get{$this->start();return $this->user ?? null;}
	}

	public function __construct(?SessionUserProvider $userProvider = null)
	{
		$this->userProvider = $userProvider ?? new SessionUserAggregate();
	}

    /**
     * @param string $message
     * @return $this
     * @throws InvalidSession
     */
    public function addFeedbackMessage(string $message):Session
    {
        $this->start();
        $this->sessionVars[$this->feedbackMessageKey] = $message;
        return $this;
    }

    /**
     * @return string|null
     * @throws InvalidSession
     */
    public function getFeedbackAndClear():?string
    {
        $this->start();
        if(isset($this->sessionVars[$this->feedbackMessageKey]))
        {
            $message = $this->sessionVars[$this->feedbackMessageKey];
            unset($this->sessionVars[$this->feedbackMessageKey]);
            return $message;
        }
        else return null;
    }

    /**
     * @param ResponseCode $code
     * @return $this
     * @throws InvalidSession
     */
    public function setFeedbackCode(ResponseCode $code):self
    {
        $this->start();
        $this->sessionVars[$this->feedbackCodeKey] = $code->value;
        return $this;
    }

    /**
     * @return ResponseCode
     * @throws InvalidSession
     */
    public function resolveResponseCode():ResponseCode
    {
        $this->start();
        if(isset($this->sessionVars[$this->feedbackCodeKey]))
        {
            $code = ResponseCode::tryFrom(
                $this->sessionVars[$this->feedbackCodeKey]) ?? ResponseCode::OK;
            unset($this->sessionVars[$this->feedbackCodeKey]);
            return $code;
        }
        else return ResponseCode::OK;
    }

	/**
	 * @param User $user
	 * @return $this
	 * @throws InvalidSession
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
	 * @throws InvalidSession
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
	 * @throws InvalidSession
	 */
	public function confirmLoggedIn():bool
	{
		$this->start();
		return $this->user !== null;
	}

	/**
	 * @return void
	 * @throws InvalidSession
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